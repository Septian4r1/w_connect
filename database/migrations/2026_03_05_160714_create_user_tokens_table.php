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
        Schema::create('user_tokens', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // JWT Access Token
            $table->text('token');

            // Refresh token untuk generate token baru
            $table->string('refresh_token', 100)->unique();

            // device login (browser / mobile)
            $table->string('device', 255)->nullable();

            // ip address user
            $table->string('ip_address', 45)->nullable();

            // expired token
            $table->timestamp('expires_at')->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | INDEX OPTIMIZATION
            |--------------------------------------------------------------------------
            */

            // cepat mencari token user
            $table->index('user_id');

            // validasi token cepat
            $table->index('token');

            // refresh token lookup
            $table->index('refresh_token');

            // cleanup token expired
            $table->index('expires_at');

            // 1 device login enforcement
            $table->unique(['user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_tokens');
    }
};
