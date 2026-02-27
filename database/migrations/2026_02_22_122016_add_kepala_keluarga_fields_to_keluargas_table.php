<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('keluargas', function (Blueprint $table) {

            // Foto Kartu Keluarga (Dokumen Wajib)
            $table->string('foto_kk')
                  ->nullable()
                  ->after('no_kk');

        });
    }

    public function down(): void
    {
        Schema::table('keluargas', function (Blueprint $table) {

            $table->dropColumn('foto_kk');

        });
    }
};
