<?php

namespace App\Services\Accounting;


use App\Models\Accounting\AccountingPeriod;

class OpenNextPeriodService
{
    public function handle(AccountingPeriod $period)
    {
        $nextPeriod = AccountingPeriod::query()
            ->where('organization_id', $period->organization_id)
            ->where(function ($q) use ($period) {
                $q->where('year', '>', $period->year)
                    ->orWhere(function ($sub) use ($period) {
                        $sub->where('year', $period->year)
                            ->where('month', '>', $period->month);
                    });
            })
            ->orderBy('year')
            ->orderBy('month')
            ->first();

        if (!$nextPeriod) {
            return;
        }

        // reset current
        AccountingPeriod::where('organization_id', $period->organization_id)
            ->update(['is_current' => false]);

        // open next
        $nextPeriod->update([
            'status' => 'OPEN',
            'is_current' => true,
            'is_closed' => false,
            'allow_transaction' => true,
            'allow_edit' => true,
            'closed_at' => null,
            'closed_by' => null,
            'locked_at' => null,
            'locked_by' => null,
        ]);

        return $nextPeriod;
    }
}
