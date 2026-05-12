<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_otps', function (Blueprint $table) {

            /**
             * HAPUS UNIQUE LAMA
             */
            $table->dropUnique('user_otps_user_id_unique');

            /**
             * TAMBAH UNIQUE BARU
             */
            $table->unique(
                ['user_id', 'type'],
                'user_otps_user_id_type_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('user_otps', function (Blueprint $table) {

            $table->dropUnique(
                'user_otps_user_id_type_unique'
            );

            $table->unique('user_id');
        });
    }
};
