<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('user_tokens', function (Blueprint $table) {
            // ubah kolom token dari TEXT ke VARCHAR(512)
            $table->string('token', 512)->change();

            // hapus index lama dulu, jika ada
            // nama default Laravel: user_tokens_token_index
            try {
                $table->dropIndex('user_tokens_token_index');
            } catch (\Exception $e) {
                // abaikan jika index belum ada
            }

            // buat index baru
            $table->index('token');
        });
    }

    public function down(): void
    {
        Schema::table('user_tokens', function (Blueprint $table) {
            // hapus index
            try {
                $table->dropIndex('user_tokens_token_index');
            } catch (\Exception $e) {
            }

            // revert tipe kolom ke TEXT
            $table->text('token')->change();
        });
    }
};
