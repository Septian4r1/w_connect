<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fund_types', function (Blueprint $table) {

            // tambahkan scope_type setelah name
            $table->enum('scope_type', ['rw', 'rt', 'both'])
                ->default('both')
                ->after('name')
                ->index();
        });
    }

    public function down(): void
    {
        Schema::table('fund_types', function (Blueprint $table) {
            $table->dropColumn('scope_type');
        });
    }
};
