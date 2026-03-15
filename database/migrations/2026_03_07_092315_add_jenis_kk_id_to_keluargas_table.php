<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('keluargas', function (Blueprint $table) {

            $table->foreignId('jenis_kk_id')
                  ->nullable()
                  ->after('rumah_id')
                  ->constrained('jenis_kks')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            /*
            ==============================
            INDEX UNTUK QUERY CEPAT
            ==============================
            */

            $table->index(['rumah_id','jenis_kk_id']);

        });
    }

    public function down(): void
    {
        Schema::table('keluargas', function (Blueprint $table) {

            $table->dropForeign(['jenis_kk_id']);
            $table->dropColumn('jenis_kk_id');

        });
    }
};
