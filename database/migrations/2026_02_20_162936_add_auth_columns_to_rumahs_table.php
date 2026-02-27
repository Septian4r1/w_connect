<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rumahs', function (Blueprint $table) {
            // Kolom autentikasi
            $table->string('password')->nullable()->after('kode_pos');
            $table->rememberToken()->after('password');

            // Status login rumah
            $table->enum('status_login', ['offline', 'online'])
                  ->default('offline')
                  ->after('remember_token')
                  ->index();
        });
    }

    public function down(): void
    {
        Schema::table('rumahs', function (Blueprint $table) {
            $table->dropColumn(['password', 'remember_token', 'status_login']);
        });
    }
};
