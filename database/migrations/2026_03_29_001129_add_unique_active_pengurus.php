<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengurus_wilayah', function (Blueprint $table) {

            // index utama performa
            $table->index(['rw_id', 'rt_id'], 'idx_rw_rt');
            $table->index(['role_id'], 'idx_role');
            $table->index(['status'], 'idx_status');

            // constraint: 1 user hanya boleh 1 jabatan aktif
            $table->unique(
                ['user_id', 'status'],
                'unique_user_jabatan_status'
            );

        });
    }

    public function down(): void
    {
        Schema::table('pengurus_wilayah', function (Blueprint $table) {

            $table->dropUnique('unique_user_jabatan_status');
            $table->dropIndex('idx_rw_rt');
            $table->dropIndex('idx_role');
            $table->dropIndex('idx_status');

        });
    }
};
