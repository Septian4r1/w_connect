<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rumahs', function (Blueprint $table) {

            $table->bigIncrements('id');

            // Relasi ke block (cluster)
            $table->foreignId('block_id')
                  ->constrained('blocks')
                  ->cascadeOnDelete();

            // Relasi ke RT (optional tapi bagus untuk query cepat)
            $table->foreignId('rt_id')
                  ->constrained('rts')
                  ->cascadeOnDelete();

            $table->string('nomor_rumah', 20);

            $table->text('alamat_lengkap')->nullable();

            $table->enum('status_hunian', [
                'huni milik sendiri',
                'kosong',
                'sewa',
                'belum huni'
            ])->default('kosong');

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | INDEX OPTIMIZATION
            |--------------------------------------------------------------------------
            */

            // Nomor rumah tidak boleh sama dalam 1 block
            $table->unique(['block_id', 'nomor_rumah']);

            $table->index('status_hunian');
            $table->index(['block_id', 'status_hunian']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rumahs');
    }
};
