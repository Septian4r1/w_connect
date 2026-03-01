<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori_umur', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 50);                  // Nama kategori
            $table->integer('umur_min');                 // Umur minimal
            $table->integer('umur_max')->nullable();     // Umur maksimal, null = tak terbatas
            $table->string('keterangan')->nullable();
            $table->timestamps();

            // =========================
            // Index untuk optimasi query
            // =========================
            $table->index('umur_min');
            $table->index('umur_max');
            $table->index(['umur_min', 'umur_max']);     // Composite index
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori_umur');
    }
};
