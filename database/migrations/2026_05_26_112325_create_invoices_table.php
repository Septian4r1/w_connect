<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {

            $table->id();

            $table->string('invoice_no')->unique();

            // =========================
            // ORGANIZATION
            // =========================
            $table->unsignedBigInteger('organization_id');
            $table->foreign('organization_id', 'fk_invoices_organization')
                ->references('id')
                ->on('organizations')
                ->cascadeOnDelete();

            // =========================
            // BILLING PERIOD
            // =========================
            $table->unsignedBigInteger('billing_period_id');
            $table->foreign('billing_period_id', 'fk_invoices_billing_period')
                ->references('id')
                ->on('ipl_billing_periods')
                ->cascadeOnDelete();

            // =========================
            // RUMAH
            // =========================
            $table->unsignedBigInteger('rumah_id');
            $table->foreign('rumah_id', 'fk_invoices_rumah')
                ->references('id')
                ->on('rumahs')
                ->cascadeOnDelete();

            // =========================
            // KELUARGA (nullable)
            // =========================
            $table->unsignedBigInteger('keluarga_id')->nullable();
            $table->foreign('keluarga_id', 'fk_invoices_keluarga')
                ->references('id')
                ->on('keluargas')
                ->nullOnDelete();

            // =========================
            // WARGA (nullable)
            // =========================
            $table->unsignedBigInteger('warga_id')->nullable();
            $table->foreign('warga_id', 'fk_invoices_warga')
                ->references('id')
                ->on('wargas')
                ->nullOnDelete();

            // =========================
            // SNAPSHOT
            // =========================
            $table->string('status_hunian_snapshot');
            $table->string('billing_rule_snapshot')->nullable();

            // =========================
            // AMOUNT
            // =========================
            $table->decimal('amount', 15, 2)->default(0);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->decimal('remaining_amount', 15, 2)->default(0);

            // =========================
            // STATUS
            // =========================
            $table->enum('status', ['unpaid', 'partial', 'paid', 'overdue'])
                ->default('unpaid')
                ->index();

            $table->boolean('is_active')->default(true)->index();

            // =========================
            // DATE
            // =========================
            $table->date('due_date')->nullable()->index();
            $table->timestamp('paid_at')->nullable();

            // =========================
            // AUDIT
            // =========================
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by', 'fk_invoices_created_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by', 'fk_invoices_updated_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            // INDEX
            $table->index(['billing_period_id', 'status']);
            $table->index(['rumah_id', 'status']);
            $table->index(['organization_id', 'billing_period_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
