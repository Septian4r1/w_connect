<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wargas', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('keluarga_id')->index();

            $table->string('nik', 20)->unique();
            $table->string('nama');
            $table->string('jenis_kelamin', 10)->index();

            $table->enum('hubungan', [
                'kepala_keluarga',
                'istri',
                'anak',
                'keluarga_lain'
            ])->index();

            $table->date('tanggal_lahir')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->string('no_hp')->nullable();

            $table->enum('status', ['aktif', 'pindah', 'meninggal'])
                ->default('aktif')
                ->index();

            $table->timestamps();

            // Buat foreign key dengan nama unik
            $table->foreign('keluarga_id', 'fk_wargas_keluarga')
                ->references('id')
                ->on('keluargas')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('wargas', function ($table) {
            $table->dropForeign('fk_wargas_keluarga');
        });

        Schema::dropIfExists('wargas');
    }
};
