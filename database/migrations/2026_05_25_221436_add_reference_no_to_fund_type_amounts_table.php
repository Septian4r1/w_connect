<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fund_type_amounts', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | REFERENCE NUMBER
            |--------------------------------------------------------------------------
            */

            $table->string('reference_no')
                ->unique()
                ->after('id');

            /*
            |--------------------------------------------------------------------------
            | INDEXING (PERFORMANCE CRITICAL)
            |--------------------------------------------------------------------------
            */

            $table->index('organization_id');
            $table->index('fund_type_id');
            $table->index('is_active');
            $table->index('created_by');
            $table->index('updated_by');
            $table->index('created_at');

            /*
            |--------------------------------------------------------------------------
            | COMPOSITE INDEX (SCALING QUERY FILTER)
            |--------------------------------------------------------------------------
            */

            $table->index([
                'organization_id',
                'fund_type_id',
            ], 'idx_org_fund');

            $table->index([
                'organization_id',
                'is_active',
            ], 'idx_org_status');
        });
    }

    public function down(): void
    {
        Schema::table('fund_type_amounts', function (Blueprint $table) {

            /*
        |--------------------------------------------------------------------------
        | DROP UNIQUE
        |--------------------------------------------------------------------------
        */

            $table->dropUnique(['reference_no']);

            /*
        |--------------------------------------------------------------------------
        | DROP COLUMN
        |--------------------------------------------------------------------------
        */

            $table->dropColumn('reference_no');
        });
    }
};
