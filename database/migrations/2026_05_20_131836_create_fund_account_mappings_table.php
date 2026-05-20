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
        Schema::create('fund_account_mappings', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | PRIMARY KEY
            |--------------------------------------------------------------------------
            */
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | FUND TYPE RELATION
            |--------------------------------------------------------------------------
            |
            | Relasi ke jenis dana.
            | 1 fund type bisa memiliki beberapa mapping di masa depan:
            | - multi company
            | - multi cabang
            | - multi periode
            | - versioning mapping
            |
            */
            $table->foreignId('fund_type_id')
                ->constrained('fund_types')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | CHART OF ACCOUNT MAPPINGS
            |--------------------------------------------------------------------------
            |
            | Mapping akun-akun utama untuk fund accounting.
            |
            */

            // akun kas utama dana
            $table->foreignId('cash_account_id')
                ->nullable()
                ->constrained('chart_of_accounts')
                ->nullOnDelete();

            // akun pendapatan dana
            $table->foreignId('revenue_account_id')
                ->nullable()
                ->constrained('chart_of_accounts')
                ->nullOnDelete();

            // akun beban dana
            $table->foreignId('expense_account_id')
                ->nullable()
                ->constrained('chart_of_accounts')
                ->nullOnDelete();

            // akun hutang / payable
            $table->foreignId('payable_account_id')
                ->nullable()
                ->constrained('chart_of_accounts')
                ->nullOnDelete();

            // akun piutang / receivable
            $table->foreignId('receivable_account_id')
                ->nullable()
                ->constrained('chart_of_accounts')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | FUTURE ERP SUPPORT
            |--------------------------------------------------------------------------
            */

            /*
            |--------------------------------------------------------------------------
            | DEFAULT MAPPING
            |--------------------------------------------------------------------------
            |
            | Menentukan mapping utama/default.
            | Future proof untuk:
            | - multiple mappings
            | - versioning
            | - branch/company separation
            |
            */
            $table->boolean('is_default')->default(true);

            /*
            |--------------------------------------------------------------------------
            | ACTIVE STATUS
            |--------------------------------------------------------------------------
            */
            $table->boolean('is_active')->default(true);

            /*
            |--------------------------------------------------------------------------
            | NOTES / AUDIT REMARKS
            |--------------------------------------------------------------------------
            |
            | Catatan accounting/auditor/admin.
            |
            */
            $table->text('notes')->nullable();

            /*
            |--------------------------------------------------------------------------
            | AUDIT USER TRACKING
            |--------------------------------------------------------------------------
            |
            | Tracking siapa yang membuat/mengubah mapping.
            | Sangat penting untuk ERP/accounting system.
            |
            */
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
            | TIMESTAMPS + SOFT DELETE
            |--------------------------------------------------------------------------
            */
            $table->timestamps();
            $table->softDeletes();

            /*
            |--------------------------------------------------------------------------
            | INDEXING (PRODUCTION OPTIMIZED)
            |--------------------------------------------------------------------------
            */

            // filter fund
            $table->index('fund_type_id');

            // filter status
            $table->index('is_active');

            // filter default mapping
            $table->index('is_default');

            // optimasi query utama
            $table->index([
                'fund_type_id',
                'is_active',
            ]);

            // optimasi default mapping
            $table->index([
                'fund_type_id',
                'is_default',
            ]);

            /*
            |--------------------------------------------------------------------------
            | OPTIONAL FUTURE UNIQUE LOGIC
            |--------------------------------------------------------------------------
            |
            | JANGAN gunakan unique(fund_type_id)
            | karena nanti bisa membatasi:
            | - multi mapping
            | - versioning
            | - branch/company structure
            |
            */
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fund_account_mappings');
    }
};
