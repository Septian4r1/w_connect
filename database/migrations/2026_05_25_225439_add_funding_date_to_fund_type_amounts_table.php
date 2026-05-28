<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rts', function (Blueprint $table) {

            // 1. Hapus FK lama (SAFE MODE)
            $table->dropForeign(['ketua_user_id']);

            // 2. Pastikan kolom tetap ada & rapi
            $table->foreignId('ketua_user_id')
                ->nullable()
                ->change(); // penting: hanya modify kolom

            // 3. Buat ulang FK yang benar
            $table->foreign('ketua_user_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('rts', function (Blueprint $table) {

            $table->dropForeign(['ketua_user_id']);

            $table->foreignId('ketua_user_id')
                ->nullable()
                ->change()
                ->constrained('users')
                ->nullOnDelete();
        });
    }
};
