<?php

namespace App\Services\Accounting;

use App\Models\Accounting\AccountingPeriod;
use App\Models\Accounting\FiscalYear;
use Illuminate\Support\Facades\DB;
use App\Models\Organization;
use App\Services\Billing\IplBillingPeriodService;
use App\Services\Accounting\OpenNextPeriodService;
use App\Events\Accounting\AccountingPeriodClosed;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PeriodService
{
    /**
     * CREATE ACCOUNTING PERIOD (FISCAL-BOUND ERP)
     */
    public function create(array $data)
    {
        $organizationId = $data['organization_id'];

        // =====================================================
        // 🔥 GET ACTIVE FISCAL YEAR (WAJIB)
        // =====================================================
        $fiscal = FiscalYear::where('organization_id', $organizationId)
            ->where('is_current', true)
            ->first();

        if (!$fiscal) {
            throw new \Exception('No active fiscal year found. Create fiscal first.');
        }

        // =====================================================
        // VALIDASI YEAR HARUS SESUAI FISCAL
        // =====================================================
        if ($data['year'] != $fiscal->year) {
            throw new \Exception('Period year must match active fiscal year');
        }

        $start = Carbon::create($data['year'], $data['month'], 1);
        $end = $start->copy()->endOfMonth();

        // =====================================================
        // DUPLICATE CHECK (ERP RULE)
        // =====================================================
        $exists = AccountingPeriod::where('year', $data['year'])
            ->where('month', $data['month'])
            ->where('organization_id', $organizationId)
            ->where('fiscal_year_id', $fiscal->id)
            ->exists();

        if ($exists) {
            throw new \Exception('Accounting period already exists for this fiscal');
        }

        $code = $this->generateCode($data['year'], $data['month'], $organizationId);
        $name = $start->format('F Y');

        // =====================================================
        // CURRENT PERIOD CONTROL (SCOPE FISCAL ONLY)
        // =====================================================
        $existingCurrent = AccountingPeriod::where('organization_id', $organizationId)
            ->where('fiscal_year_id', $fiscal->id)
            ->where('is_current', true)
            ->exists();

        $isCurrent = false;

        if (!$existingCurrent) {
            $isCurrent =
                $data['year'] == now()->year &&
                $data['month'] == now()->month;
        }

        if ($isCurrent) {
            AccountingPeriod::where('organization_id', $organizationId)
                ->where('fiscal_year_id', $fiscal->id)
                ->update(['is_current' => false]);
        }

        // =====================================================
        // CREATE PERIOD
        // =====================================================
        return AccountingPeriod::create([
            'fiscal_year_id' => $fiscal->id,

            'code' => $code,
            'name' => $name,

            'year' => $data['year'],
            'month' => $data['month'],

            'start_date' => $start,
            'end_date' => $end,

            'organization_id' => $organizationId,

            'status' => 'OPEN',
            'is_current' => $isCurrent,
            'is_closed' => false,

            'allow_transaction' => true,
            'allow_edit' => true,

            'created_by' => Auth::id(),
        ]);
    }

    /**
     * SET CURRENT PERIOD (FISCAL SCOPED)
     */
    public function setCurrent(int $id)
    {
        $period = AccountingPeriod::findOrFail($id);

        AccountingPeriod::where('organization_id', $period->organization_id)
            ->where('fiscal_year_id', $period->fiscal_year_id)
            ->update(['is_current' => false]);

        $period->update(['is_current' => true]);

        return $period;
    }

    /**
     * CLOSE PERIOD
     */
    public function close(int $id)
    {
        $period = AccountingPeriod::findOrFail($id);

        if ($period->status !== 'OPEN') {
            throw new \Exception('Only OPEN period can be closed');
        }

        $period->update([
            'status' => 'CLOSED',
            'is_closed' => true,
            'is_current' => false,
            'allow_transaction' => false,
            'allow_edit' => false,

            'closed_at' => now(),
            'closed_by' => Auth::id(),
        ]);

        return $period;
    }

    /**
     * LOCK PERIOD
     */
    public function lock(int $id)
    {
        $period = AccountingPeriod::findOrFail($id);

        if ($period->status !== 'CLOSED') {
            throw new \Exception('Period must be CLOSED before LOCKED');
        }

        $period->update([
            'status' => 'LOCKED',
            'locked_at' => now(),
            'locked_by' => Auth::id(),
        ]);

        return $period;
    }

    /**
     * GENERATE FULL YEAR (FISCAL AUTO LINKED)
     */
    public function generateYear(int $year, ?int $organizationId = null)
    {
        $fiscal = FiscalYear::where('organization_id', $organizationId)
            ->where('is_current', true)
            ->first();

        if (!$fiscal) {
            throw new \Exception('No active fiscal year found');
        }

        $created = [];

        for ($month = 1; $month <= 12; $month++) {

            $exists = AccountingPeriod::where('year', $year)
                ->where('month', $month)
                ->where('fiscal_year_id', $fiscal->id)
                ->exists();

            if ($exists) {
                continue;
            }

            $start = Carbon::create($year, $month, 1);
            $end = $start->copy()->endOfMonth();

            $created[] = AccountingPeriod::create([
                'fiscal_year_id' => $fiscal->id,

                'code' => $this->generateCode($year, $month, $organizationId),
                'name' => $start->format('F Y'),

                'year' => $year,
                'month' => $month,

                'start_date' => $start,
                'end_date' => $end,

                'organization_id' => $organizationId,

                'status' => 'OPEN',
                'is_current' => ($year == now()->year && $month == now()->month),
                'is_closed' => false,

                'allow_transaction' => true,
                'allow_edit' => true,

                'created_by' => Auth::id(),
            ]);
        }

        return $created;
    }

    /**
     * CODE GENERATOR
     */
    private function generateCode(int $year, int $month, ?int $organizationId): string
    {
        $org = Organization::findOrFail($organizationId);

        return $org->code . '-PRD-' .
            str_pad($month, 2, '0', STR_PAD_LEFT) . '-' .
            $year;
    }

    /**
     * STATS (FISCAL SCOPED)
     */
    public function getStats(): array
    {
        $total = AccountingPeriod::count();
        $open = AccountingPeriod::where('status', 'OPEN')->count();
        $closed = AccountingPeriod::where('status', 'CLOSED')->count();
        $locked = AccountingPeriod::where('status', 'LOCKED')->count();

        return [
            'total' => $total,
            'open' => $open,
            'closed' => $closed,
            'locked' => $locked,
            'active_rate' => $total > 0 ? round(($open / $total) * 100, 2) : 0,
        ];
    }

    /**
     * CHANGE PERIOD STATUS (ERP WORKFLOW)
     */
    public function changeStatus(int $id, string $status)
    {
        return DB::transaction(function () use ($id, $status) {

            // =========================
            // LOCK ROW (ANTI RACE CONDITION)
            // =========================
            $period = AccountingPeriod::where('id', $id)
                ->lockForUpdate()
                ->firstOrFail();

            $status = strtoupper($status);

            if (!in_array($status, ['OPEN', 'CLOSED', 'LOCKED', 'ARCHIVED'])) {
                throw new \Exception('Invalid accounting period status');
            }

            // =========================
            // GUARD: NO DOUBLE UPDATE
            // =========================
            if ($period->status === $status) {
                return $period;
            }

        // =========================
        // USER CONTEXT (FIX TYPE SAFE)
        // =========================

            /** @var \App\Models\User|null $user */
            $user = Auth::user();

            if (!$user) {
                throw new \Exception("Unauthorized");
            }

            // cache role check biar clean
            $isSuperAdmin = $user->hasRole('super_admin');

            // =========================
            // RULE ENGINE (IMMUTABLE STATE)
            // =========================

            // LOCKED → hanya super admin
            if ($period->status === 'LOCKED' && !$isSuperAdmin) {
                throw new \Exception("This period is locked. Only super admin can modify it.");
            }

            // CLOSED → tidak bisa reopen kecuali super admin
            if (
                $period->status === 'CLOSED'
                && $status === 'OPEN'
                && !$isSuperAdmin
            ) {
                throw new \Exception("Closed period cannot be reopened except super admin.");
            }

            // =========================
            // DEFAULT STATE
            // =========================
            $data = [
                'status' => $status,
                'allow_transaction' => false,
                'allow_edit' => false,
                'is_current' => false,
            ];

            // =========================
            // OPEN
            // =========================
            if ($status === 'OPEN') {

                AccountingPeriod::where('organization_id', $period->organization_id)
                    ->update(['is_current' => false]);

                $data = array_merge($data, [
                    'allow_transaction' => true,
                    'allow_edit' => true,
                    'is_current' => true,
                    'is_closed' => false,
                    'closed_at' => null,
                    'closed_by' => null,
                    'locked_at' => null,
                    'locked_by' => null,
                ]);
            }

            // =========================
            // CLOSED
            // =========================
            if ($status === 'CLOSED') {

                $data['is_closed'] = true;
                $data['closed_at'] = now();
                $data['closed_by'] = $user->id;
            }

            // =========================
            // LOCKED
            // =========================
            if ($status === 'LOCKED') {

                $data['is_closed'] = true;
                $data['closed_at'] = $period->closed_at ?? now();
                $data['closed_by'] = $period->closed_by ?? $user->id;
                $data['locked_at'] = now();
                $data['locked_by'] = $user->id;
            }

            // =========================
            // ARCHIVED
            // =========================
            if ($status === 'ARCHIVED') {

                $data['is_closed'] = true;
                $data['allow_transaction'] = false;
                $data['allow_edit'] = false;
            }

            // =========================
            // UPDATE
            // =========================
            $period->update($data);


            // =========================
            // EVENT LAYER (CLOSED ONLY)
            // =========================
            if ($status === 'CLOSED') {

                // =========================
                // OPEN NEXT PERIOD
                // =========================
                $nextPeriod = app(OpenNextPeriodService::class)
                    ->handle($period);


                // =========================
                // AUTO GENERATE IPL BILLING PERIOD
                // =========================
                if ($nextPeriod) {

                    app(IplBillingPeriodService::class)
                        ->generateFromAccountingPeriod($nextPeriod);
                }

                // =========================
                // DISPATCH ERP EVENT
                // =========================
                event(new AccountingPeriodClosed(
                    period: $period,
                    closedBy: Auth::id(),
                    closedAt: now(),
                    source: 'manual',
                    context: [
                        'organization_id' => $period->organization_id,
                        'period_code' => $period->code,
                        'next_period_id' => $nextPeriod?->id,
                    ]
                ));
            }
            return $period;
        });
    }
}
