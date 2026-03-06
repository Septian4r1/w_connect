<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_otps', function (Blueprint $table) {
            // Tambahkan kolom device_fingerprint
            $table->string('device_fingerprint', 255)->nullable()->after('expired_at');

            // Optional: buat index supaya pencarian lebih cepat
            $table->index('device_fingerprint');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_otps', function (Blueprint $table) {
            $table->dropColumn('device_fingerprint');
        });
    }
};
