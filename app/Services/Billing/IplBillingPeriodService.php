<?php

namespace App\Services\Billing;

use App\Models\Accounting\AccountingPeriod;
use App\Events\Billing\IplBillingPeriodCreated;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Accounting\IplBillingPeriod;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class IplBillingPeriodService
{
    /*
    |--------------------------------------------------------------------------
    | CREATE PERIOD
    |--------------------------------------------------------------------------
    */

    public function create(array $data): IplBillingPeriod
    {
        return DB::transaction(function () use ($data) {

            $period = IplBillingPeriod::create($data);

            /*
            |--------------------------------------------------------------------------
            | DISPATCH EVENT
            |--------------------------------------------------------------------------
            */

            event(new IplBillingPeriodCreated(
                billingPeriod: $period,
                context: [
                    'organization_id' => $period->organization_id,
                    'billing_period_id' => $period->id,
                ],
                source: 'system',
            ));

            return $period;
        });
    }
    /*
    |--------------------------------------------------------------------------
    | OPEN PERIOD
    |--------------------------------------------------------------------------
    */

    public function open(
        IplBillingPeriod $period
    ): IplBillingPeriod {

        if (!$period->isDraft()) {

            throw ValidationException::withMessages([
                'period' => 'Only draft period can be opened.',
            ]);
        }

        $period->update([
            'status' => IplBillingPeriod::STATUS_OPEN,
        ]);

        return $period->fresh();
    }

    /*
    |--------------------------------------------------------------------------
    | CLOSE PERIOD
    |--------------------------------------------------------------------------
    */

    public function close(
        IplBillingPeriod $period,
        ?User $user = null
    ): IplBillingPeriod {

        if (!$period->isGeneratedStatus()) {

            throw ValidationException::withMessages([
                'period' => 'Billing must be generated before closing.',
            ]);
        }

        $period->update([

            'status' => IplBillingPeriod::STATUS_CLOSED,

            'closed_at' => now(),

            'closed_by' => $user?->id,
        ]);

        return $period->fresh();
    }

    /*
    |--------------------------------------------------------------------------
    | LOCK PERIOD
    |--------------------------------------------------------------------------
    */

    public function lock(
        IplBillingPeriod $period
    ): IplBillingPeriod {

        $period->update([
            'is_locked' => true,
        ]);

        return $period->fresh();
    }

    /*
    |--------------------------------------------------------------------------
    | UNLOCK PERIOD
    |--------------------------------------------------------------------------
    */

    public function unlock(
        IplBillingPeriod $period
    ): IplBillingPeriod {

        $period->update([
            'is_locked' => false,
        ]);

        return $period->fresh();
    }

    /*
    |--------------------------------------------------------------------------
    | CAN GENERATE
    |--------------------------------------------------------------------------
    */

    public function canGenerate(
        IplBillingPeriod $period
    ): bool {

        return $period->canGenerate();
    }

    /*
|--------------------------------------------------------------------------
| GENERATE FROM ACCOUNTING PERIOD
|--------------------------------------------------------------------------
*/

    public function generateFromAccountingPeriod(
        AccountingPeriod $period
    ): IplBillingPeriod {

        return DB::transaction(function () use ($period) {

            // =====================================================
            // DUPLICATE CHECK
            // =====================================================

            $exists = IplBillingPeriod::where(
                'accounting_period_id',
                $period->id
            )->first();

            if ($exists) {
                return $exists;
            }

            // =====================================================
            // DATE
            // =====================================================

            $invoiceDate = $period->start_date;

            $dueDate = $period->start_date
                ->copy()
                ->addDays(14);

            // =====================================================
            // CODE
            // =====================================================

            $code =
                'IPL-' .
                $period->organization->name .
                '-' .
                $period->year .
                '-' .
                str_pad(
                    $period->month,
                    2,
                    '0',
                    STR_PAD_LEFT
                );

            // =====================================================
            // CREATE BILLING PERIOD
            // =====================================================

            $billingPeriod = $this->create([

                'organization_id' => $period->organization_id,
                'accounting_period_id' => $period->id,
                'code' => $code,
                'name' => 'IPL Billing ' .
                    $period->organization->name .
                    ' - ' .
                    $period->start_date->format('M Y'),
                'billing_type' => 'IPL',
                'category' => 'RECURRING',
                'description' =>
                'Auto generated from accounting period ' .
                    $period->code,
                'invoice_date' => $invoiceDate,
                'due_date' => $dueDate,
                'grace_days' => 7,
                'status' => IplBillingPeriod::STATUS_OPEN,
                'is_locked' => false,
                'is_generated' => false,
                'total_invoices' => 0,
                'total_amount' => 0,
                'total_paid' => 0,
                'total_unpaid' => 0,
                'created_by' => Auth::id(),
                'notes' => 'System auto generated billing period',
            ]);

            return $billingPeriod;
        });
    }
}
