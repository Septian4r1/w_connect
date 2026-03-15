<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nomor_pengajuans', function (Blueprint $table) {

            // 🔥 drop index lama
            $table->dropUnique('uq_prefix_tanggal');
            $table->dropIndex('idx_tanggal_prefix');

        });

        // 🔥 ubah tipe kolom (pakai DB statement biar aman)
        DB::statement("ALTER TABLE nomor_pengajuans MODIFY prefix VARCHAR(100)");

        Schema::table('nomor_pengajuans', function (Blueprint $table) {

            // 🔥 unique baru
            $table->unique('prefix', 'uq_prefix');

            // index tambahan
            $table->index('tanggal', 'idx_tanggal');

        });
    }

    public function down(): void
    {
        Schema::table('nomor_pengajuans', function (Blueprint $table) {

            $table->dropUnique('uq_prefix');
            $table->dropIndex('idx_tanggal');

        });

        DB::statement("ALTER TABLE nomor_pengajuans MODIFY prefix CHAR(10)");

        Schema::table('nomor_pengajuans', function (Blueprint $table) {

            $table->unique(['prefix','tanggal'], 'uq_prefix_tanggal');
            $table->index(['tanggal','prefix'], 'idx_tanggal_prefix');

        });
    }
};
