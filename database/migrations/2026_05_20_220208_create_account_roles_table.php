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
        Schema::create('account_roles', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | BASIC INFORMATION
            |--------------------------------------------------------------------------
            */

            $table->string('code', 50)->unique();

            $table->string('name');

            $table->text('description')->nullable();

            /*
            |--------------------------------------------------------------------------
            | ACCOUNTING VALIDATION
            |--------------------------------------------------------------------------
            */

            $table->enum('coa_type', [
                'asset',
                'liability',
                'equity',
                'revenue',
                'expense',
            ]);

            $table->enum('normal_balance', [
                'debit',
                'credit',
            ]);

            /*
            |--------------------------------------------------------------------------
            | SYSTEM CONTROL
            |--------------------------------------------------------------------------
            */

            $table->boolean('is_system')->default(true);

            $table->boolean('is_active')->default(true);

            /*
            |--------------------------------------------------------------------------
            | AUDIT
            |--------------------------------------------------------------------------
            */

            $table->timestamps();

            $table->softDeletes();

            /*
            |--------------------------------------------------------------------------
            | INDEXES
            |--------------------------------------------------------------------------
            */

            $table->index('coa_type');
            $table->index('normal_balance');
            $table->index('is_system');
            $table->index('is_active');
            $table->index('deleted_at');

            // Optional composite index
            $table->index(['is_active', 'coa_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_roles');
    }
};
