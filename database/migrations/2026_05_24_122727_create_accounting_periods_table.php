<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accounting_periods', function (Blueprint $table) {
            $table->id();

            // =========================
            // IDENTITAS PERIOD
            // =========================
            $table->string('code')->unique();
            // contoh: 2026-01

            $table->string('name');
            // January 2026

            $table->smallInteger('year');
            $table->tinyInteger('month');

            $table->date('start_date');
            $table->date('end_date');

            // =========================
            // SCOPE (FUTURE MULTI RW / MULTI ORG READY)
            // =========================
            $table->unsignedBigInteger('organization_id')->nullable();
            // nanti kalau SaaS / multi RW

            // =========================
            // STATUS CONTROL (ERP CORE)
            // =========================
            $table->enum('status', ['OPEN', 'CLOSED', 'LOCKED', 'ARCHIVED'])
                ->default('OPEN');

            $table->boolean('is_current')->default(false);
            $table->boolean('is_closed')->default(false);

            // =========================
            // AUDIT CLOSING
            // =========================
            $table->timestamp('closed_at')->nullable();
            $table->unsignedBigInteger('closed_by')->nullable();

            $table->timestamp('locked_at')->nullable();
            $table->unsignedBigInteger('locked_by')->nullable();

            // =========================
            // DATA INTEGRITY CONTROL
            // =========================
            $table->boolean('allow_transaction')->default(true);
            // kalau false = hard lock (no insert journal/invoice)

            $table->boolean('allow_edit')->default(true);
            // false = immutable period

            // =========================
            // META
            // =========================
            $table->text('notes')->nullable();

            $table->timestamps();

            // =========================
            // INDEXING (ERP OPTIMIZED)
            // =========================

            // anti duplicate period
            $table->unique(['year', 'month', 'organization_id']);

            // fast lookup active period
            $table->index(['status', 'is_current']);

            // reporting & closing history
            $table->index('closed_at');

            // performance dashboard query
            $table->index(['organization_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounting_periods');
    }
};
