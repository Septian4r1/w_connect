<?php

namespace App\Providers;

use App\Events\Billing\IplBillingPeriodCreated;
use App\Listeners\Billing\GenerateInvoiceListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Event to listener mappings
     */
    protected $listen = [
        IplBillingPeriodCreated::class => [
            GenerateInvoiceListener::class,
        ],
    ];

    public function boot(): void
    {
        //
    }
}
