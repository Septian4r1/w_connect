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
        Schema::table('blocks', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | DROP FOREIGN KEY
            |--------------------------------------------------------------------------
            */

            $table->dropForeign(
                'blocks_organization_id_foreign'
            );

            /*
            |--------------------------------------------------------------------------
            | DROP COLUMN
            |--------------------------------------------------------------------------
            */

            $table->dropColumn(
                'organization_id'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blocks', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | ORGANIZATION
            |--------------------------------------------------------------------------
            */

            $table->foreignId('organization_id')
                ->nullable()
                ->after('rt_id')
                ->constrained('organizations')
                ->nullOnDelete();
        });
    }
};
