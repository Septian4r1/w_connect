<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_files', function (Blueprint $table) {

            $table->id();

            // ✅ FK manual ke pengajuan
            $table->unsignedBigInteger('pengajuan_id');

            $table->foreign('pengajuan_id', 'fk_files_pengajuan')
                ->references('id')
                ->on('pengajuan_perubahan')
                ->cascadeOnDelete();

            $table->index('pengajuan_id');

            $table->string('nama_file');
            $table->string('path_file');

            $table->string('jenis_dokumen')->nullable()->index();

            $table->timestamps();

            // composite index
            $table->index(['pengajuan_id', 'jenis_dokumen']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_files');
    }
};
