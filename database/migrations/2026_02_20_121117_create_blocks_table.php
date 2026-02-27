<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blocks', function (Blueprint $table) {
            $table->bigIncrements('id'); // lebih aman untuk skala besar

            $table->string('nama_blok', 50);
            $table->text('keterangan')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])
                  ->default('aktif');

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | INDEX OPTIMIZATION
            |--------------------------------------------------------------------------
            */

            // Unique index (otomatis jadi index)
            $table->unique('nama_blok');

            // Index untuk filter status
            $table->index('status');

            // Composite index jika sering query:
            // where status = 'aktif' order by nama_blok
            $table->index(['status', 'nama_blok']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blocks');
    }
};
