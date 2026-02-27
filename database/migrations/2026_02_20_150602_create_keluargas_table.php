<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keluargas', function (Blueprint $table) {

            $table->id();

            // Rumah bisa punya banyak KK, jadi hapus unique()
            $table->foreignId('rumah_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Nomor KK tetap unik nasional
            $table->string('no_kk', 20)->unique();

            // Status KK
            $table->enum('status', ['aktif', 'pindah', 'nonaktif'])
                  ->default('aktif')
                  ->index();

            $table->timestamps();

            /*
            |----------------------------------------------------------------------
            | INDEX OPTIMIZATION
            |----------------------------------------------------------------------
            */

            $table->index('rumah_id'); // penting untuk query cepat per rumah

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keluargas');
    }
};
