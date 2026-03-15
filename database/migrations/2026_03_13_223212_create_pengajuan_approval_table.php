<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_approval', function (Blueprint $table) {

            $table->id();

            // ✅ FK manual ke pengajuan
            $table->unsignedBigInteger('pengajuan_id');

            $table->foreign('pengajuan_id', 'fk_approval_pengajuan')
                  ->references('id')
                  ->on('pengajuan_perubahan')
                  ->cascadeOnDelete();

            $table->index('pengajuan_id');

            // level approval
            $table->string('level')->index();

            $table->enum('status', [
                'pending',
                'approve',
                'reject'
            ])->default('pending')->index();

            // ✅ FK manual ke users
            $table->unsignedBigInteger('approved_by')->nullable();

            $table->foreign('approved_by', 'fk_approval_user')
                  ->references('id')
                  ->on('users')
                  ->nullOnDelete();

            $table->index('approved_by');

            $table->timestamp('approved_at')->nullable();
            $table->text('catatan')->nullable();

            $table->timestamps();

            $table->index(['pengajuan_id', 'status']);
            $table->index(['level', 'status']);

            // supaya tidak double approval
            $table->unique(['pengajuan_id', 'level']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_approval');
    }
};
