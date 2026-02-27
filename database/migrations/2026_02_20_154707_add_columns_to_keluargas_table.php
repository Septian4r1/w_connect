<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('keluargas', function (Blueprint $table) {
            // Enum ya / tidak
            $table->enum('ktp_setempat', ['ya', 'tidak'])
                  ->default('ya')
                  ->after('status');

            // Enum kependudukan
            $table->enum('kependudukan', ['tetap', 'domisili'])
                  ->default('tetap')
                  ->after('ktp_setempat');

            // Alamat KK tambahan
            $table->text('alamat_kk')->nullable()->after('kependudukan');
            $table->string('desa')->nullable()->after('alamat_kk');
            $table->string('kelurahan')->nullable()->after('desa');
        });
    }

    public function down(): void
    {
        Schema::table('keluargas', function (Blueprint $table) {
            $table->dropColumn([
                'ktp_setempat',
                'kependudukan',
                'alamat_kk',
                'desa',
                'kelurahan'
            ]);
        });
    }
};
