<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('wargas', function (Blueprint $table) {
            $table->string('province', 255)->nullable()->after('tempat_lahir')->comment('Provinsi warga');
        });
    }

    public function down()
    {
        Schema::table('wargas', function (Blueprint $table) {
            $table->dropColumn('province');
        });
    }
};
