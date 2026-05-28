<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ipl_billing_periods', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | RELATION
            |--------------------------------------------------------------------------
            */

            $table->foreignId('organization_id')
                ->constrained('organizations')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('accounting_period_id')
                ->constrained('accounting_periods')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | BILLING INFORMATION
            |--------------------------------------------------------------------------
            */

            $table->string('code')->unique();

            $table->string('name');

            $table->enum('billing_type', [
                'IPL',
                'DENDA',
                'KHUSUS',
                'DLL'
            ])->default('IPL');


            $table->enum('category', [
                'REGULAR',
                'RECURRING',
                'SPECIAL'
            ])->default('REGULAR');



            $table->text('description')->nullable();

            /*
            |--------------------------------------------------------------------------
            | BILLING DATE
            |--------------------------------------------------------------------------
            */

            $table->date('invoice_date');

            $table->date('due_date');

            $table->integer('grace_days')->default(0);

            /*
            |--------------------------------------------------------------------------
            | STATUS
            |--------------------------------------------------------------------------
            */

            $table->enum('status', [
                'DRAFT',
                'ACTIVE',
                'CLOSED',
                'CANCELLED'
            ])->default('DRAFT');

            $table->boolean('is_locked')->default(false);

            $table->boolean('is_generated')->default(false);

            /*
            |--------------------------------------------------------------------------
            | BILLING SUMMARY
            |--------------------------------------------------------------------------
            */

            $table->unsignedInteger('total_invoices')->default(0);

            $table->decimal('total_amount', 18, 2)->default(0);

            $table->decimal('total_paid', 18, 2)->default(0);

            $table->decimal('total_unpaid', 18, 2)->default(0);

            /*
            |--------------------------------------------------------------------------
            | AUDIT
            |--------------------------------------------------------------------------
            */

            $table->timestamp('generated_at')->nullable();

            $table->timestamp('closed_at')->nullable();

            $table->timestamp('cancelled_at')->nullable();

            $table->foreignId('closed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | NOTES
            |--------------------------------------------------------------------------
            */

            $table->text('notes')->nullable();

            /*
            |--------------------------------------------------------------------------
            | SYSTEM
            |--------------------------------------------------------------------------
            */

            $table->timestamps();

            $table->softDeletes();

            /*
            |--------------------------------------------------------------------------
            | INDEXES
            |--------------------------------------------------------------------------
            */

            $table->index('organization_id');

            $table->index('accounting_period_id');

            $table->index('billing_type');

            $table->index('status');

            $table->index('due_date');

            /*
            |--------------------------------------------------------------------------
            | UNIQUE BUSINESS RULE
            |--------------------------------------------------------------------------
            |
            | 1 billing type per organization & accounting period
            |
            */

            $table->unique([
                'organization_id',
                'accounting_period_id',
                'billing_type'
            ], 'uniq_org_period_billing_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ipl_billing_periods');
    }
};
