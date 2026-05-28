<?php

namespace App\Events\Accounting;

use Carbon\Carbon;
use App\Models\Accounting\AccountingPeriod;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AccountingPeriodClosed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public AccountingPeriod $period,
        public ?int $closedBy = null,
        public ?Carbon $closedAt = null,
        public string $source = 'manual',
        public array $context = []
    ) {}
}
