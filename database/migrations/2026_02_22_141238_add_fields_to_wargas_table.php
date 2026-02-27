<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wargas', function (Blueprint $table) {

            // Status perkawinan
            $table->enum('status_perkawinan', [
                'belum_kawin',
                'kawin',
                'cerai_hidup',
                'cerai_mati'
            ])->nullable()->after('hubungan')->index();

            // Agama
            $table->string('agama', 50)
                ->nullable()
                ->after('status_perkawinan')
                ->index();

            // Pendidikan
            $table->string('pendidikan', 100)
                ->nullable()
                ->after('agama')
                ->index();

            // Email
            $table->string('email')
                ->nullable()
                ->after('no_hp')
                ->index();

            // Golongan darah
            $table->enum('golongan_darah', [
                'A','B','AB','O'
            ])
            ->nullable()
            ->after('email')
            ->index();

            // Foto KTP
            $table->string('foto_ktp')
                ->nullable()
                ->after('golongan_darah');

            // Foto warga
            $table->string('foto')
                ->nullable()
                ->after('foto_ktp');

        });
    }


    public function down(): void
    {
        Schema::table('wargas', function (Blueprint $table) {

            $table->dropColumn([
                'status_perkawinan',
                'agama',
                'pendidikan',
                'email',
                'golongan_darah',
                'foto_ktp',
                'foto'
            ]);

        });
    }
};
