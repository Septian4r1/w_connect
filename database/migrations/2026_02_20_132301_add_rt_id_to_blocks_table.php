<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blocks', function (Blueprint $table) {

            // Tambah kolom rt_id (nullable dulu jika sudah ada data)
            $table->unsignedBigInteger('rt_id')
                  ->nullable()
                  ->after('id');
        });

        /*
        Jika sudah ada data lama,
        isi dulu rt_id sebelum dibuat NOT NULL.
        Contoh sementara set ke RT id 1.
        Sesuaikan dengan kondisi data kamu.
        */
        DB::statement('UPDATE blocks SET rt_id = 1 WHERE rt_id IS NULL');

        Schema::table('blocks', function (Blueprint $table) {

            // Ubah jadi NOT NULL
            $table->unsignedBigInteger('rt_id')
                  ->nullable(false)
                  ->change();

            // Hapus unique lama nama_blok
            $table->dropUnique(['nama_blok']);

            // Tambah foreign key
            $table->foreign('rt_id')
                  ->references('id')
                  ->on('rts')
                  ->onDelete('cascade');

            // Unique per RT
            $table->unique(['rt_id', 'nama_blok']);
        });
    }

    public function down(): void
    {
        Schema::table('blocks', function (Blueprint $table) {

            $table->dropUnique(['rt_id', 'nama_blok']);
            $table->dropForeign(['rt_id']);
            $table->dropColumn('rt_id');

            // Kembalikan unique lama
            $table->unique('nama_blok');
        });
    }
};
