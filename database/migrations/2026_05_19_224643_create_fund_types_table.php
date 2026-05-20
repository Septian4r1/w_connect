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
        Schema::create('fund_types', function (Blueprint $table) {
            $table->id();

            /**
             * Kode unik jenis dana
             *
             * Contoh:
             * RW
             * RT
             * SOS
             * SMP
             */
            $table->string('code', 20)->unique();

            /**
             * Nama jenis dana
             *
             * Contoh:
             * Dana RW
             * Dana RT
             * Dana Sosial
             */
            $table->string('name', 100);

            /**
             * Deskripsi tambahan
             */
            $table->text('description')->nullable();

            /**
             * Status aktif
             *
             * true  = aktif
             * false = nonaktif
             */
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            /**
             * Index optimasi query
             */
            $table->index('is_active');
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fund_types');
    }
};
