<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rts', function (Blueprint $table) {

            // pastikan tidak ada FK lama
            $table->dropForeign(['ketua_user_id']);

            // bikin ulang FK yang benar
            $table->foreign('ketua_user_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete()
                ->name('fk_rts_ketua_user_id');
        });
    }

    public function down(): void
    {
        Schema::table('rts', function (Blueprint $table) {

            $table->dropForeign('fk_rts_ketua_user_id');

            $table->foreign('ketua_user_id')
                ->references('id')
                ->on('users');
        });
    }
};
