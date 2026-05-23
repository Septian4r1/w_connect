<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organizations', function (Blueprint $table) {

            // ❌ DROP kolom yang tidak dipakai
            if (Schema::hasColumn('organizations', 'rw_id')) {
                $table->dropColumn('rw_id');
            }

            if (Schema::hasColumn('organizations', 'rt_id')) {
                $table->dropColumn('rt_id');
            }

            // ✔ pastikan soft delete ada
            if (!Schema::hasColumn('organizations', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {

            // rollback kalau perlu
            $table->unsignedBigInteger('rw_id')->nullable();
            $table->unsignedBigInteger('rt_id')->nullable();

            $table->dropSoftDeletes();
        });
    }
};
