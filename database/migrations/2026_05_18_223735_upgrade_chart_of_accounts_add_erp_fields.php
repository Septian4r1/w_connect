<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('chart_of_accounts', function (Blueprint $table) {

            // ❌ JANGAN TAMBAH UNIQUE LAGI
            // code sudah unique dari migration lama

            // OPENING BALANCE
            if (!Schema::hasColumn('chart_of_accounts', 'opening_balance')) {
                $table->decimal('opening_balance', 15, 2)->default(0)->after('normal_balance');
            }

            // CURRENCY
            if (!Schema::hasColumn('chart_of_accounts', 'currency')) {
                $table->string('currency', 10)->default('IDR')->after('opening_balance');
            }

            // IS POSTABLE
            if (!Schema::hasColumn('chart_of_accounts', 'is_postable')) {
                $table->boolean('is_postable')->default(0)->after('is_header');
            }

            // PARENT PATH
            if (!Schema::hasColumn('chart_of_accounts', 'parent_path')) {
                $table->string('parent_path')->nullable()->index()->after('parent_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('chart_of_accounts', function (Blueprint $table) {

            if (Schema::hasColumn('chart_of_accounts', 'opening_balance')) {
                $table->dropColumn('opening_balance');
            }

            if (Schema::hasColumn('chart_of_accounts', 'currency')) {
                $table->dropColumn('currency');
            }

            if (Schema::hasColumn('chart_of_accounts', 'is_postable')) {
                $table->dropColumn('is_postable');
            }

            if (Schema::hasColumn('chart_of_accounts', 'parent_path')) {
                $table->dropColumn('parent_path');
            }

            // ❌ JANGAN DROP UNIQUE CODE DI SINI
            // karena itu dari migration awal (core schema)
        });
    }
};
