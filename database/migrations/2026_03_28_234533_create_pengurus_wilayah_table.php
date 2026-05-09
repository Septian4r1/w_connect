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
        Schema::create('pengurus_wilayah', function (Blueprint $table) {
            $table->bigIncrements('id'); // aman untuk data besar

            // akun login
            $table->unsignedBigInteger('user_id');

            // role dari spatie
            $table->unsignedBigInteger('role_id');

            // wilayah
            $table->unsignedBigInteger('rw_id')->nullable();
            $table->unsignedBigInteger('rt_id')->nullable();

            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | INDEX OPTIMIZATION
            |--------------------------------------------------------------------------
            */

            // login check wilayah user
            $table->index('user_id');

            // cari pengurus per RW
            $table->index('rw_id');

            // cari pengurus per RT
            $table->index('rt_id');

            // kombinasi query wilayah
            $table->index(['rw_id', 'rt_id']);

            // query berdasarkan role dalam wilayah
            $table->index(['role_id', 'rw_id', 'rt_id']);

            /*
            |--------------------------------------------------------------------------
            | UNIQUE CONSTRAINT
            |--------------------------------------------------------------------------
            */

            // satu user hanya punya satu jabatan aktif
            $table->unique(['user_id']);

            /*
            |--------------------------------------------------------------------------
            | FOREIGN KEY
            |--------------------------------------------------------------------------
            */

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();

            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->cascadeOnDelete();

            $table->foreign('rw_id')
                ->references('id')
                ->on('rws')
                ->cascadeOnDelete();

            $table->foreign('rt_id')
                ->references('id')
                ->on('rts')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengurus_wilayah');
    }
};
