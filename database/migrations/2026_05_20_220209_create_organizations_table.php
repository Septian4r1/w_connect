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
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();

            /*
            |------------------------------------------------------
            | BASIC IDENTITY
            |------------------------------------------------------
            */
            $table->string('type')->index();
            // rw, rt, vendor, lembaga, system

            $table->string('code')->nullable()->index();
            $table->string('name');

            /*
            |------------------------------------------------------
            | HIERARCHY (RW → RT → CHILD STRUCTURE)
            |------------------------------------------------------
            */
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('organizations')
                ->nullOnDelete();

            /*
            |------------------------------------------------------
            | RELATIONAL KE STRUCTURE LAMA
            |------------------------------------------------------
            | ini supaya kamu gampang mapping dari tabel lama
            */
            $table->unsignedBigInteger('rw_id')->nullable()->index();
            $table->unsignedBigInteger('rt_id')->nullable()->index();

            /*
            |------------------------------------------------------
            | STATUS CONTROL
            |------------------------------------------------------
            */
            $table->boolean('is_active')->default(true);

            /*
            |------------------------------------------------------
            | AUDIT
            |------------------------------------------------------
            */
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
