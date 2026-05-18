<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * =========================================================================
     * RUN MIGRATION
     * =========================================================================
     */
    public function up(): void
    {
        Schema::create('chart_of_accounts', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | PRIMARY KEY
            |--------------------------------------------------------------------------
            */

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | PARENT ACCOUNT
            |--------------------------------------------------------------------------
            |
            | Self relation untuk hierarchy/tree account.
            |
            | Contoh:
            |
            | ASSET
            |   └── CASH & BANK
            |          └── CASH RW
            |
            */

            $table->unsignedBigInteger('parent_id')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | ACCOUNT CODE
            |--------------------------------------------------------------------------
            |
            | Contoh:
            |
            | 10000
            | 11100
            | 41110
            |
            */

            $table->string('code', 20)
                ->unique();

            /*
            |--------------------------------------------------------------------------
            | ACCOUNT NAME
            |--------------------------------------------------------------------------
            */

            $table->string('name', 200);

            /*
            |--------------------------------------------------------------------------
            | LEVEL HIERARCHY
            |--------------------------------------------------------------------------
            |
            | Level 1 = ROOT
            | Level 2 = GROUP
            | Level 3 = DETAIL
            |
            */

            $table->unsignedInteger('level')
                ->default(1);

            /*
            |--------------------------------------------------------------------------
            | ACCOUNT TYPE
            |--------------------------------------------------------------------------
            */

            $table->enum('type', [
                'asset',
                'liability',
                'equity',
                'revenue',
                'expense'
            ]);

            /*
            |--------------------------------------------------------------------------
            | NORMAL BALANCE
            |--------------------------------------------------------------------------
            |
            | asset   = debit
            | expense = debit
            | liability = credit
            | equity = credit
            | revenue = credit
            |
            */

            $table->enum('normal_balance', [
                'debit',
                'credit'
            ]);

            /*
            |--------------------------------------------------------------------------
            | HEADER ACCOUNT
            |--------------------------------------------------------------------------
            |
            | TRUE  = hanya parent/group
            | FALSE = akun transaksi
            |
            */

            $table->boolean('is_header')
                ->default(false);

            /*
            |--------------------------------------------------------------------------
            | ACTIVE STATUS
            |--------------------------------------------------------------------------
            */

            $table->boolean('is_active')
                ->default(true);

            /*
            |--------------------------------------------------------------------------
            | DESCRIPTION
            |--------------------------------------------------------------------------
            */

            $table->text('description')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | ERP EXTRA
            |--------------------------------------------------------------------------
            */

            $table->integer('sort_order')
                ->default(0);

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | INDEXES
            |--------------------------------------------------------------------------
            */

            $table->index('parent_id', 'coa_parent_idx');

            $table->index('code', 'coa_code_idx');

            $table->index('type', 'coa_type_idx');

            $table->index('is_active', 'coa_active_idx');

            $table->index([
                'parent_id',
                'is_active'
            ], 'coa_parent_active_idx');

            $table->index([
                'type',
                'is_active'
            ], 'coa_type_active_idx');

            /*
            |--------------------------------------------------------------------------
            | FOREIGN KEY MANUAL
            |--------------------------------------------------------------------------
            */

            $table->foreign('parent_id', 'coa_parent_fk')
                ->references('id')
                ->on('chart_of_accounts')
                ->nullOnDelete();
        });
    }

    /**
     * =========================================================================
     * ROLLBACK
     * =========================================================================
     */
    public function down(): void
    {
        Schema::table('chart_of_accounts', function (Blueprint $table) {

            $table->dropForeign('coa_parent_fk');
        });

        Schema::dropIfExists('chart_of_accounts');
    }
};
