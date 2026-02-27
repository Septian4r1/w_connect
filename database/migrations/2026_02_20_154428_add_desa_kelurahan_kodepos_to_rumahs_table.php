<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rumahs', function (Blueprint $table) {
            $table->string('desa')->nullable()->after('alamat_lengkap');
            $table->string('kelurahan')->nullable()->after('desa');
            $table->string('kode_pos', 10)->nullable()->after('kelurahan');
        });
    }

    public function down(): void
    {
        Schema::table('rumahs', function (Blueprint $table) {
            $table->dropColumn(['desa', 'kelurahan', 'kode_pos']);
        });
    }
};
