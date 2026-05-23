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
        Schema::table('pengurus_wilayah', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | ORGANIZATION
            |--------------------------------------------------------------------------
            */
            $table->foreignId('organization_id')

                ->nullable()

                ->after('role_id')

                ->constrained('organizations')

                ->nullOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengurus_wilayah', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | DROP FOREIGN KEY
            |--------------------------------------------------------------------------
            */
            $table->dropForeign([
                'organization_id'
            ]);

            /*
            |--------------------------------------------------------------------------
            | DROP COLUMN
            |--------------------------------------------------------------------------
            */
            $table->dropColumn('organization_id');

        });
    }
};
