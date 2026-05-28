<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |---------------------------------------------
        | CREATE TABLE
        |---------------------------------------------
        */
        Schema::create('invoices', function (Blueprint $table) {

            $table->id();
            $table->string('invoice_no')->unique();

            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('billing_period_id');
            $table->unsignedBigInteger('rumah_id');

            $table->unsignedBigInteger('keluarga_id')->nullable();
            $table->unsignedBigInteger('warga_id')->nullable();

            $table->string('status_hunian_snapshot');
            $table->string('billing_rule_snapshot')->nullable();

            $table->decimal('amount', 15, 2)->default(0);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->decimal('remaining_amount', 15, 2)->default(0);

            $table->enum('status', ['unpaid', 'partial', 'paid', 'overdue'])
                ->default('unpaid');

            $table->boolean('is_active')->default(true);

            $table->date('due_date')->nullable();
            $table->timestamp('paid_at')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['billing_period_id', 'status'], 'idx_invoice_period_status');
            $table->index(['rumah_id', 'status'], 'idx_invoice_rumah_status');
            $table->index(['organization_id', 'billing_period_id'], 'idx_invoice_org_period');
        });

        /*
        |---------------------------------------------
        | FOREIGN KEYS (SAFE WRAPPER)
        |---------------------------------------------
        */
        try {
            Schema::table('invoices', function (Blueprint $table) {

                $table->foreign('organization_id', 'fk_invoices_organization')
                    ->references('id')
                    ->on('organizations')
                    ->cascadeOnDelete();

                $table->foreign('billing_period_id', 'fk_invoices_billing_period')
                    ->references('id')
                    ->on('ipl_billing_periods')
                    ->cascadeOnDelete();

                $table->foreign('rumah_id', 'fk_invoices_rumah')
                    ->references('id')
                    ->on('rumahs')
                    ->cascadeOnDelete();

                $table->foreign('keluarga_id', 'fk_invoices_keluarga')
                    ->references('id')
                    ->on('keluargas')
                    ->nullOnDelete();

                $table->foreign('warga_id', 'fk_invoices_warga')
                    ->references('id')
                    ->on('wargas')
                    ->nullOnDelete();

                $table->foreign('created_by', 'fk_invoices_created_by')
                    ->references('id')
                    ->on('users')
                    ->nullOnDelete();

                $table->foreign('updated_by', 'fk_invoices_updated_by')
                    ->references('id')
                    ->on('users')
                    ->nullOnDelete();
            });
        } catch (\Throwable $e) {
            // ignore FK conflict di environment berbeda
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
