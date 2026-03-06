<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_tokens', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('token', 512); // token VARCHAR agar bisa di-index
            $table->string('refresh_token', 100)->unique();
            $table->string('device', 255)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            // INDEXES
            $table->index('user_id');
            $table->index('token');
            $table->index('refresh_token');
            $table->index('expires_at');

            $table->unique(['user_id']); // 1 device login enforcement
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_tokens');
    }
};
