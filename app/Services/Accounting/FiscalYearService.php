<?php

namespace App\Services\Accounting;

use App\Models\Accounting\FiscalYear;
use App\Models\Accounting\AccountingPeriod;
use App\Models\Organization;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class FiscalYearService
{
    /**
     * CREATE FISCAL + AUTO GENERATE 12 PERIODS
     */
    public function create(array $data)
    {
        $orgId = $data['organization_id'];

        $startYear = $data['year'];

        $startDate = isset($data['start_date'])
            ? Carbon::parse($data['start_date'])
            : Carbon::create($startYear, 1, 1);

        $endDate = isset($data['end_date'])
            ? Carbon::parse($data['end_date'])
            : Carbon::create($startYear, 12, 31);

        // cek fiscal aktif
        $existingCurrent = FiscalYear::where('organization_id', $orgId)
            ->where('is_current', true)
            ->first();

        if ($existingCurrent) {
            throw new \Exception('Active fiscal year already exists');
        }

        $prevFiscal = FiscalYear::where('organization_id', $orgId)
            ->orderByDesc('year')
            ->first();

        $fiscal = FiscalYear::create([
            'code' => $this->generateCode($orgId, $startYear),
            'name' => "Fiscal Year {$startYear}",
            'year' => $startYear,

            'start_date' => $startDate,
            'end_date' => $endDate,

            'organization_id' => $orgId,

            // ✅ TAMBAH INI
            'notes' => $data['notes'] ?? null,

            'status' => 'OPEN',
            'is_current' => true,
            'is_closed' => false,

            'previous_fiscal_id' => $prevFiscal?->id,
            'created_by' => Auth::id(),
        ]);

        FiscalYear::where('organization_id', $orgId)
            ->where('id', '!=', $fiscal->id)
            ->update(['is_current' => false]);

        $this->generatePeriods($fiscal);

        return $fiscal;
    }

    /**
     * AUTO GENERATE PERIODS (12 BULAN)
     */
    private function generatePeriods(FiscalYear $fiscal)
    {
        $start = Carbon::parse($fiscal->start_date)->startOfMonth();
        $end = Carbon::parse($fiscal->end_date)->endOfMonth();

        $currentYear = now()->year;
        $currentMonth = now()->month;

        $periodIndex = 1;

        while ($start->lte($end)) {

            $periodEnd = $start->copy()->endOfMonth();

            // jangan lewat dari fiscal end
            if ($periodEnd->gt($end)) {
                $periodEnd = $end;
            }

            // =========================
            // ERP STATUS LOGIC
            // =========================
            if ($start->year < $currentYear || ($start->year == $currentYear && $start->month < $currentMonth)) {
                $status = 'LOCKED';
                $isClosed = true;
                $allowTransaction = false;
                $allowEdit = false;
                $isCurrent = false;
            } elseif ($start->year == $currentYear && $start->month == $currentMonth) {
                $status = 'OPEN';
                $isClosed = false;
                $allowTransaction = true;
                $allowEdit = true;
                $isCurrent = true;
            } else {
                $status = 'CLOSED';
                $isClosed = false;
                $allowTransaction = false;
                $allowEdit = false;
                $isCurrent = false;
            }

            // =========================
            // CREATE PERIOD
            // =========================
            AccountingPeriod::create([
                'fiscal_year_id' => $fiscal->id,
                'organization_id' => $fiscal->organization_id,

                'code' => $fiscal->code
                    . '-PRD-'
                    . str_pad($start->month, 2, '0', STR_PAD_LEFT)
                    . '-' . strtoupper($start->format('M')),

                'name' => 'Accounting Period - ' . $start->format('F Y'),

                'year' => $start->year,
                'month' => $start->month,

                'start_date' => $start->copy()->startOfMonth(),
                'end_date' => $periodEnd,

                'status' => $status,
                'is_current' => $isCurrent,
                'is_closed' => $isClosed,

                'allow_transaction' => $allowTransaction,
                'allow_edit' => $allowEdit,

                'created_by' => Auth::id(),
            ]);

            $start->addMonth();
            $periodIndex++;
        }
    }

    /**
     * SET CURRENT FISCAL
     */
    public function setCurrent(int $id)
    {
        $fiscal = FiscalYear::findOrFail($id);

        FiscalYear::where('organization_id', $fiscal->organization_id)
            ->update(['is_current' => false]);

        $fiscal->update(['is_current' => true]);

        return $fiscal;
    }

    /**
     * CLOSE FISCAL (SAP STYLE)
     */
    public function close(int $id)
    {
        $fiscal = FiscalYear::findOrFail($id);

        if ($fiscal->status !== 'OPEN') {
            throw new \Exception('Only OPEN fiscal can be closed');
        }

        $fiscal->update([
            'status' => 'CLOSED',
            'is_closed' => true,
            'is_current' => false,
            'closed_at' => now(),
            'closed_by' => Auth::id(),
        ]);

        return $fiscal;
    }

    private function generateCode(int $orgId, int $year): string
    {
        $org = Organization::findOrFail($orgId);

        return $org->code . '-FY-' . $year;
    }
}
