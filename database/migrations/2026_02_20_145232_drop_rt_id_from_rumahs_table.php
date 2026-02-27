<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rumahs', function (Blueprint $table) {

            // Drop foreign key dulu
            $table->dropForeign(['rt_id']);

            // Drop index otomatis jika ada
            $table->dropColumn('rt_id');
        });
    }

    public function down(): void
    {
        Schema::table('rumahs', function (Blueprint $table) {

            $table->foreignId('rt_id')
                  ->constrained('rts')
                  ->cascadeOnDelete();
        });
    }
};
