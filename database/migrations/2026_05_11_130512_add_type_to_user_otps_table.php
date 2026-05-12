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
        Schema::table('user_otps', function (Blueprint $table) {

            /**
             * 🔥 TYPE OTP
             * login / reset_password / dll
             */
            $table->string('type')
                ->default('login')
                ->after('user_id');

            /**
             * 🔥 UNIQUE
             */
            $table->unique(
                ['user_id', 'type'],
                'user_otps_user_type_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_otps', function (Blueprint $table) {

            $table->dropUnique(
                'user_otps_user_type_unique'
            );

            $table->dropColumn('type');
        });
    }
};
