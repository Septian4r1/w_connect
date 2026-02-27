<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rts', function (Blueprint $table) {

            // Tambah kolom rw_id (sementara nullable dulu)
            $table->unsignedBigInteger('rw_id')
                  ->nullable()
                  ->after('id');
        });

        /*
        ⚠️ Jika sudah ada data lama di tabel rts,
        kamu WAJIB isi rw_id dulu sebelum dibuat NOT NULL + foreign key.

        Contoh sementara: set semua ke RW id 1
        (Sesuaikan dengan kebutuhan kamu)
        */
        DB::statement('UPDATE rts SET rw_id = 1 WHERE rw_id IS NULL');

        Schema::table('rts', function (Blueprint $table) {

            // Ubah jadi NOT NULL
            $table->unsignedBigInteger('rw_id')->nullable(false)->change();

            // Tambah foreign key
            $table->foreign('rw_id')
                  ->references('id')
                  ->on('rws')
                  ->onDelete('cascade');

            // Unique constraint
            $table->unique(['rw_id', 'nama_rt']);
        });
    }

    public function down(): void
    {
        Schema::table('rts', function (Blueprint $table) {

            $table->dropUnique(['rw_id', 'nama_rt']);
            $table->dropForeign(['rw_id']);
            $table->dropColumn('rw_id');
        });
    }
};
