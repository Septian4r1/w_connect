<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan_perubahan', function (Blueprint $table) {
            // ✅ Hapus foreign key lama jika ada
            if (Schema::hasColumn('pengajuan_perubahan', 'created_by')) {
                $table->dropForeign('fk_pengajuan_user');
            }

            // ✅ Pastikan created_by nullable
            $table->unsignedBigInteger('created_by')->nullable()->change();

            // ✅ Buat FK baru ke wargas.id
            $table->foreign('created_by', 'fk_pengajuan_user')
                ->references('id')
                ->on('wargas')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan_perubahan', function (Blueprint $table) {
            // Hapus FK yang baru
            if (Schema::hasColumn('pengajuan_perubahan', 'created_by')) {
                $table->dropForeign('fk_pengajuan_user');
            }

            // Kembalikan kolom created_by tetap nullable
            $table->unsignedBigInteger('created_by')->nullable()->change();

            // Buat FK lama lagi ke users.id (sesuai kondisi awal)
            $table->foreign('created_by', 'fk_pengajuan_user')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }
};
