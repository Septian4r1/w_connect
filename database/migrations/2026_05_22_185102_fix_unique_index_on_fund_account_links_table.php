<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | CHECK OLD UNIQUE EXISTS
        |--------------------------------------------------------------------------
        */
        $oldIndex = DB::select("
            SHOW INDEX
            FROM fund_account_links
            WHERE Key_name = 'fund_account_org_unique'
        ");

        /*
        |--------------------------------------------------------------------------
        | DROP OLD UNIQUE
        |--------------------------------------------------------------------------
        */
        if (!empty($oldIndex)) {

            Schema::table('fund_account_links', function (Blueprint $table) {

                $table->dropUnique('fund_account_org_unique');
            });
        }

        /*
        |--------------------------------------------------------------------------
        | CHECK NEW UNIQUE EXISTS
        |--------------------------------------------------------------------------
        */
        $newIndex = DB::select("
            SHOW INDEX
            FROM fund_account_links
            WHERE Key_name = 'unique_mapping'
        ");

        /*
        |--------------------------------------------------------------------------
        | CREATE NEW UNIQUE
        |--------------------------------------------------------------------------
        */
        if (empty($newIndex)) {

            Schema::table('fund_account_links', function (Blueprint $table) {

                $table->unique(
                    [
                        'fund_type_id',
                        'organization_id',
                        'account_role_id',
                        'coa_id',
                    ],
                    'unique_mapping'
                );
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        /*
        |--------------------------------------------------------------------------
        | DROP NEW UNIQUE
        |--------------------------------------------------------------------------
        */
        $newIndex = DB::select("
            SHOW INDEX
            FROM fund_account_links
            WHERE Key_name = 'unique_mapping'
        ");

        if (!empty($newIndex)) {

            Schema::table('fund_account_links', function (Blueprint $table) {

                $table->dropUnique('unique_mapping');
            });
        }

        /*
        |--------------------------------------------------------------------------
        | RESTORE OLD UNIQUE
        |--------------------------------------------------------------------------
        */
        $oldIndex = DB::select("
            SHOW INDEX
            FROM fund_account_links
            WHERE Key_name = 'fund_account_org_unique'
        ");

        if (empty($oldIndex)) {

            Schema::table('fund_account_links', function (Blueprint $table) {

                $table->unique(
                    [
                        'fund_type_id',
                        'account_role_id',
                        'organization_id',
                        'is_default',
                    ],
                    'fund_account_org_unique'
                );
            });
        }
    }
};
