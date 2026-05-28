<?php

namespace App\Events\Billing;

use App\Models\Accounting\IplBillingPeriod;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class IplBillingPeriodCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public IplBillingPeriod $billingPeriod,
        public array $context = [],
        public string $source = 'system',
    ) {}
}
