<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::table('keluargas', function (Blueprint $table) {

            // hapus kolom lama
            $table->dropColumn(['desa','kelurahan']);

            // kolom baru
            $table->string('desa_kelurahan')->nullable()->after('alamat_kk');

            $table->string('kecamatan')->nullable()->after('desa_kelurahan');

            $table->string('kota_kabupaten')->nullable()->after('kecamatan');

            $table->string('provinsi')->nullable()->after('kota_kabupaten');

        });
    }

    public function down()
    {
        Schema::table('keluargas', function (Blueprint $table) {

            $table->string('desa')->nullable();
            $table->string('kelurahan')->nullable();

            $table->dropColumn([
                'desa_kelurahan',
                'kecamatan',
                'kota_kabupaten',
                'provinsi'
            ]);

        });
    }

};
