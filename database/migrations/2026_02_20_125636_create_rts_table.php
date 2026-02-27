<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rts', function (Blueprint $table) {

            $table->bigIncrements('id');

            // Contoh: RT 01, RT 02
            $table->string('nama_rt', 20)->unique();

            // Ketua RT adalah warga (user)
            $table->foreignId('ketua_user_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete()
                  ->index();

            $table->enum('status', ['aktif', 'nonaktif'])
                  ->default('aktif');

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | INDEX OPTIMIZATION
            |--------------------------------------------------------------------------
            */

            $table->index('status');
            $table->index(['status', 'nama_rt']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rts');
    }
};
