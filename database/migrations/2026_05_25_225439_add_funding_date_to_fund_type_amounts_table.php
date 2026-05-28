<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |---------------------------------------------
        | STEP 1: DROP FOREIGN KEY (SAFE)
        |---------------------------------------------
        */
        try {
            Schema::table('rts', function (Blueprint $table) {
                $table->dropForeign(['ketua_user_id']);
            });
        } catch (\Throwable $e) {
            // FK tidak ada → ignore
        }

        /*
        |---------------------------------------------
        | STEP 2: MODIFY COLUMN (SAFE)
        |---------------------------------------------
        */
        try {
            Schema::table('rts', function (Blueprint $table) {
                $table->unsignedBigInteger('ketua_user_id')
                    ->nullable()
                    ->change();
            });
        } catch (\Throwable $e) {
            // column change gagal → ignore (environment beda)
        }

        /*
        |---------------------------------------------
        | STEP 3: CREATE FOREIGN KEY (SAFE)
        |---------------------------------------------
        */
        try {
            Schema::table('rts', function (Blueprint $table) {
                $table->foreign('ketua_user_id')
                    ->references('id')
                    ->on('users')
                    ->nullOnDelete();
            });
        } catch (\Throwable $e) {
            // FK sudah ada / duplicate → ignore
        }
    }

    public function down(): void
    {
        /*
        |---------------------------------------------
        | SAFE DROP FK
        |---------------------------------------------
        */
        try {
            Schema::table('rts', function (Blueprint $table) {
                $table->dropForeign(['ketua_user_id']);
            });
        } catch (\Throwable $e) {
            // ignore
        }

        /*
        |---------------------------------------------
        | RESTORE COLUMN RELATION
        |---------------------------------------------
        */
        try {
            Schema::table('rts', function (Blueprint $table) {
                $table->foreign('ketua_user_id')
                    ->references('id')
                    ->on('users');
            });
        } catch (\Throwable $e) {
            // ignore
        }
    }
};
