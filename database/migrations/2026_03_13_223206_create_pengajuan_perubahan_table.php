<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_perubahan', function (Blueprint $table) {

            $table->id();

            $table->string('no_pengajuan')->unique()->index();

            // ✅ FK manual (anti duplicate error)
            $table->unsignedBigInteger('warga_id');

            $table->foreign('warga_id', 'fk_pengajuan_warga')
                ->references('id')
                ->on('wargas')
                ->cascadeOnDelete();

            $table->index('warga_id');

            $table->string('nama_pengaju');

            $table->string('jenis_pengajuan')->index();

            $table->string('field_perubahan')->nullable();

            $table->text('data_awal')->nullable();
            $table->text('data_baru')->nullable();

            $table->text('alasan')->nullable();

            $table->enum('status', [
                'pending',
                'proses',
                'ditolak',
                'selesai'
            ])->default('pending')->index();

            // FK users juga manual biar konsisten
            $table->unsignedBigInteger('created_by')->nullable();

            $table->foreign('created_by', 'fk_pengajuan_user')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->index('created_by');

            $table->timestamps();

            $table->index(['warga_id', 'status']);
            $table->index(['jenis_pengajuan', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_perubahan');
    }
};
