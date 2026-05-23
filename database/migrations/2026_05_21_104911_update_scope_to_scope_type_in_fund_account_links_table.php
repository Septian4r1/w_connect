<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fund_account_links', function (Blueprint $table) {

            // scope_type
            if (!Schema::hasColumn('fund_account_links', 'scope_type')) {
                $table->string('scope_type')
                    ->nullable()
                    ->after('account_role_id');
            }

            // scope_id
            if (!Schema::hasColumn('fund_account_links', 'scope_id')) {
                $table->unsignedBigInteger('scope_id')
                    ->nullable()
                    ->after('scope_type');
            }

            /**
             * INDEX - jangan pakai check doctrine
             * Laravel akan skip error kalau index sudah ada (atau biarkan DB handle)
             */
            try {
                $table->index('scope_type');
            } catch (\Throwable $e) {
            }

            try {
                $table->index('scope_id');
            } catch (\Throwable $e) {
            }
        });
    }

    public function down(): void
    {
        Schema::table('fund_account_links', function (Blueprint $table) {

            if (Schema::hasColumn('fund_account_links', 'scope_type')) {
                $table->dropColumn('scope_type');
            }

            if (Schema::hasColumn('fund_account_links', 'scope_id')) {
                $table->dropColumn('scope_id');
            }
        });
    }
};
