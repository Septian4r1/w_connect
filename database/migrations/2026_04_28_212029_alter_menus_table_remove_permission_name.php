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
        Schema::table('menus', function (Blueprint $table) {

            // HAPUS INDEX dulu (biar aman di beberapa DB)
            $table->dropIndex(['permission_name', 'is_active']);

            // DROP COLUMN lama
            $table->dropColumn('permission_name');

            // (OPSIONAL REKOMENDASI TERBAIK)
            // kalau kamu mau pakai pivot, sebenarnya ini TIDAK PERLU
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {

            // balikin lagi kalau rollback
            $table->string('permission_name')->nullable()->index();

            $table->index(['permission_name', 'is_active']);
        });
    }
};
