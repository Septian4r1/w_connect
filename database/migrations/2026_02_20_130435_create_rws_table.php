<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rws', function (Blueprint $table) {

            $table->bigIncrements('id');

            $table->string('nama_rw', 20)->unique();

            $table->unsignedBigInteger('ketua_user_id')->nullable();

            $table->enum('status', ['aktif', 'nonaktif'])
                ->default('aktif');

            $table->timestamps();

            // 🔥 Manual FK dengan nama constraint jelas
            $table->foreign('ketua_user_id', 'fk_rws_ketua_user')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->index('status');
            $table->index(['status', 'nama_rw']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rws');
    }
};
