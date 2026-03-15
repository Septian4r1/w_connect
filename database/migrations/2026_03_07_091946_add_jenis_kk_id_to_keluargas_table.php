<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jenis_kks', function (Blueprint $table) {

            $table->id();

            // contoh : KK Utama, KK Tambahan, Kontrakan
            $table->string('nama', 50)->unique();

            $table->text('keterangan')->nullable();

            $table->timestamps();

            /*
            ==============================
            INDEX OPTIMIZATION
            ==============================
            */

            $table->index('nama');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jenis_kks');
    }
};
