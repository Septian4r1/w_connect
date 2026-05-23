<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengurus_wilayah', function (Blueprint $table) {

            // DROP UNIQUE INDEX (pakai DB statement karena MySQL index name)
            DB::statement('ALTER TABLE pengurus_wilayah DROP INDEX pengurus_wilayah_user_id_unique');
            DB::statement('ALTER TABLE pengurus_wilayah DROP INDEX unique_user_jabatan_status');

            // ADD DATE COLUMNS (HISTORI JABATAN)
            $table->date('start_date')->nullable()->after('status');
            $table->date('end_date')->nullable()->after('start_date');

            // ADD NORMAL INDEX
            $table->index('user_id', 'idx_user_id');
            $table->index('status', 'idx_user_status');
            $table->index('start_date', 'idx_start_date');
            $table->index('end_date', 'idx_end_date');
        });
    }

    public function down(): void
    {
        Schema::table('pengurus_wilayah', function (Blueprint $table) {

            // DROP INDEX
            $table->dropIndex('idx_user_id');
            $table->dropIndex('idx_user_status');
            $table->dropIndex('idx_start_date');
            $table->dropIndex('idx_end_date');

            // DROP COLUMNS
            $table->dropColumn(['start_date', 'end_date']);
        });

        Schema::table('pengurus_wilayah', function (Blueprint $table) {

            // restore unique index (rollback safety)
            $table->unique('user_id', 'pengurus_wilayah_user_id_unique');
            $table->unique(['user_id', 'status'], 'unique_user_jabatan_status');
        });
    }
};
