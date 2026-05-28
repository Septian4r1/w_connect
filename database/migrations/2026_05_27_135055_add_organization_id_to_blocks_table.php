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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blocks', function (Blueprint $table) {

            $table->dropForeign([
                'organization_id'
            ]);

            $table->dropColumn(
                'organization_id'
            );
        });
    }
};
