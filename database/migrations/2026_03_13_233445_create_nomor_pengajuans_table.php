<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nomor_pengajuans', function (Blueprint $table) {

            $table->engine = 'InnoDB';

            $table->id();

            // contoh: PGJ, SUR, ADM, BNT
            $table->char('prefix', 10);

            // format: YYYYMMDD
            $table->char('tanggal', 8);

            // nomor terakhir
            $table->unsignedInteger('nomor_terakhir')->default(0);

            $table->timestamps();

            // 🔥 UNIQUE sudah otomatis index
            $table->unique(['prefix', 'tanggal'], 'uq_prefix_tanggal');

            // 🔥 tambahan index untuk generator cepat
            $table->index(['tanggal', 'prefix'], 'idx_tanggal_prefix');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nomor_pengajuans');
    }
};
