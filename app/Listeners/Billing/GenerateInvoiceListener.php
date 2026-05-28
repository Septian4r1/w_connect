<?php

namespace App\Listeners\Billing;

use App\Events\Billing\IplBillingPeriodCreated;
use App\Models\Accounting\IplBillingPeriod;
use App\Models\Accounting\InvoiceIPL;
use App\Models\Rumah;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class GenerateInvoiceListener
{
    /**
     * Handle the event.
     */
    public function handle(
        IplBillingPeriodCreated $event
    ): void {

        $period = $event->billingPeriod;

        Log::info('START GENERATE IPL INVOICE', [
            'billing_period_id' => $period->id,
            'billing_code' => $period->code,
            'organization_id' => $period->organization_id,
        ]);

        try {

            // =====================================================
            // VALIDASI ORGANIZATION
            // =====================================================

            if ($period->organization?->type !== 'rt') {

                Log::warning('SKIP GENERATE INVOICE: organization bukan RT', [
                    'billing_period_id' => $period->id,
                ]);

                return;
            }

            // =====================================================
            // AMBIL RUMAH
            // =====================================================

            $rumahs = Rumah::query()
                ->whereHas('block.rt', function ($q) use ($period) {
                    $q->where('id', $period->organization_id);
                })
                ->get();

            Log::info('RUMAH DITEMUKAN', [
                'total_rumah' => $rumahs->count(),
                'billing_period_id' => $period->id,
            ]);

            if ($rumahs->isEmpty()) {

                Log::warning('TIDAK ADA RUMAH UNTUK GENERATE INVOICE', [
                    'billing_period_id' => $period->id,
                ]);

                return;
            }

            // =====================================================
            // SUMMARY
            // =====================================================

            $totalInvoice = 0;
            $totalAmount = 0;

            // =====================================================
            // GENERATE INVOICE
            // =====================================================

            foreach ($rumahs as $rumah) {

                try {

                    Log::info('PROCESS RUMAH', [
                        'rumah_id' => $rumah->id,
                    ]);

                    // =====================================================
                    // ANTI DUPLICATE
                    // =====================================================

                    $exists = InvoiceIPL::query()
                        ->where('billing_period_id', $period->id)
                        ->where('rumah_id', $rumah->id)
                        ->exists();

                    if ($exists) {

                        Log::warning('INVOICE SUDAH ADA', [
                            'billing_period_id' => $period->id,
                            'rumah_id' => $rumah->id,
                        ]);

                        continue;
                    }

                    // =====================================================
                    // AMOUNT
                    // =====================================================

                    $amount = (float) ($rumah->ipl_amount ?? 0);

                    // =====================================================
                    // NOTE DI TABLE RT DAN RW DI TAMBAH ORGANIZATION ID 
                    // =====================================================

                    $data = [

                        'invoice_no' => $this->generateInvoiceNo(
                            $period,
                            $rumah
                        ),

                        'organization_id' => $period->organization_id,

                        'billing_period_id' => $period->id,

                        'rumah_id' => $rumah->id,

                        'keluarga_id' => $rumah->keluarga_id ?? null,

                        'warga_id' => $rumah->warga_id ?? null,

                        'status_hunian_snapshot' =>
                        $rumah->status_hunian ?? 'UNKNOWN',

                        'billing_rule_snapshot' => $period->code,

                        'amount' => $amount,

                        'paid_amount' => 0.00,

                        'remaining_amount' => $amount,

                        'status' => 'unpaid',

                        'is_active' => true,

                        'due_date' => $period->due_date,

                        'created_by' => Auth::id(),
                    ];


                    // =====================================================
                    // CREATE
                    // =====================================================

                    $invoice = InvoiceIPL::create($data);

                    Log::info('INVOICE CREATED SUCCESS', [
                        'invoice_id' => $invoice->id,
                        'invoice_no' => $invoice->invoice_no,
                        'rumah_id' => $rumah->id,
                    ]);

                    $totalInvoice++;
                    $totalAmount += $amount;
                } catch (Throwable $e) {

                    Log::error('FAILED CREATE INVOICE', [

                        'message' => $e->getMessage(),

                        'line' => $e->getLine(),

                        'file' => $e->getFile(),

                        'billing_period_id' => $period->id,

                        'rumah_id' => $rumah->id,
                    ]);

                    continue;
                }
            }

            // =====================================================
            // UPDATE SUMMARY PERIOD
            // =====================================================

            $period->update([

                'is_generated' => true,

                'status' => IplBillingPeriod::STATUS_GENERATED,

                'total_invoices' => $totalInvoice,

                'total_amount' => $totalAmount,

                'total_unpaid' => $totalAmount,
            ]);

            Log::info('FINISH GENERATE IPL INVOICE', [

                'billing_period_id' => $period->id,

                'total_invoice' => $totalInvoice,

                'total_amount' => $totalAmount,
            ]);
        } catch (Throwable $e) {

            Log::critical('FATAL GENERATE IPL INVOICE ERROR', [

                'message' => $e->getMessage(),

                'line' => $e->getLine(),

                'file' => $e->getFile(),

                'billing_period_id' => $period->id ?? null,
            ]);
        }
    }

    /**
     * Generate invoice number.
     */
    private function generateInvoiceNo(
        IplBillingPeriod $period,
        Rumah $rumah
    ): string {

        return 'INV-' .
            $period->code . '-' .
            $rumah->id . '-' .
            Str::upper(Str::random(4));
    }
}
