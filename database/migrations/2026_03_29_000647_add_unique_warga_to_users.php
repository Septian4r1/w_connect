<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // wajib warga
            $table->unsignedBigInteger('warga_id')->nullable(false)->change();

            // relasi ke tabel warga
            $table->foreign('warga_id', 'users_warga_fk')
                ->references('id')
                ->on('wargas')
                ->cascadeOnDelete();

            // 1 warga hanya boleh 1 akun
            $table->unique('warga_id', 'users_warga_unique');

        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropForeign('users_warga_fk');
            $table->dropUnique('users_warga_unique');

            // balik lagi nullable kalau rollback
            $table->unsignedBigInteger('warga_id')->nullable()->change();

        });
    }
};
