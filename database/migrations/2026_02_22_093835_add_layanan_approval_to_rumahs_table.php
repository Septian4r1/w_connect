<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rumahs', function (Blueprint $table) {
            // Kolom baru untuk persetujuan layanan
            $table->tinyInteger('layanan_approval')->default(0)->after('status_login')
                  ->comment('0 = belum disetujui, 1 = sudah disetujui');
        });
    }

    public function down(): void
    {
        Schema::table('rumahs', function (Blueprint $table) {
            $table->dropColumn('layanan_approval');
        });
    }
};
