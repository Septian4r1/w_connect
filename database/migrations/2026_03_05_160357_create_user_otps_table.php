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
        Schema::create('user_otps', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('otp', 6);

            $table->timestamp('expired_at');

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | OPTIMASI INDEX
            |--------------------------------------------------------------------------
            */

            // setiap user hanya punya 1 OTP aktif
            $table->unique('user_id');

            // mempercepat verifikasi OTP
            $table->index(['user_id', 'otp']);

            // mempercepat cleanup OTP expired
            $table->index('expired_at');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_otps');
    }
};
