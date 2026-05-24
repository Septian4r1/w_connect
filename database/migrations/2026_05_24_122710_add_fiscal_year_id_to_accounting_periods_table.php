<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fiscal_years', function (Blueprint $table) {
            $table->id();

            // ===============================
            // IDENTITAS FISCAL YEAR
            // ===============================
            $table->string('code')->unique()->nullable();
            $table->string('name');
            $table->integer('year');

            // ===============================
            // PERIOD CONTROL
            // ===============================
            $table->date('start_date');
            $table->date('end_date');

            // ===============================
            // RELATION ORGANIZATION
            // ===============================
            $table->foreignId('organization_id')
                ->constrained()
                ->cascadeOnDelete();

            // ===============================
            // IFRS STATUS CONTROL
            // ===============================
            $table->enum('status', ['OPEN', 'CLOSED', 'LOCKED'])
                ->default('OPEN');

            $table->boolean('is_current')->default(false);
            $table->boolean('is_closed')->default(false);

            // ===============================
            // YEAR CONTINUITY (IMPORTANT)
            // ===============================
            $table->unsignedBigInteger('previous_fiscal_id')->nullable();

            $table->foreign('previous_fiscal_id')
                ->references('id')
                ->on('fiscal_years')
                ->nullOnDelete();

            // ===============================
            // AUDIT CLOSING
            // ===============================
            $table->timestamp('closed_at')->nullable();
            $table->unsignedBigInteger('closed_by')->nullable();

            $table->timestamp('locked_at')->nullable();
            $table->unsignedBigInteger('locked_by')->nullable();

            // ===============================
            // GENERAL NOTES & AUDIT
            // ===============================
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();

            $table->timestamps();

            // ===============================
            // INDEXES (PERFORMANCE ERP)
            // ===============================
            $table->index('organization_id');
            $table->index('year');
            $table->index('status');
            $table->index('is_current');
            $table->index('previous_fiscal_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fiscal_years');
    }
};
