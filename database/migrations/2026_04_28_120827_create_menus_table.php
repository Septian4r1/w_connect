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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('route')->nullable();
            $table->string('icon')->nullable();

            $table->string('permission_name')->nullable()->index();

            // FIXED FK
            $table->unsignedBigInteger('parent_id')->nullable()->index();

            $table->unsignedInteger('order')->default(0)->index();
            $table->boolean('is_active')->default(true)->index();

            $table->timestamps();

            // FOREIGN KEY (MANUAL NAME)
            $table->foreign('parent_id', 'fk_menus_parent_id')
                ->references('id')
                ->on('menus')
                ->cascadeOnDelete();

            // INDEX
            $table->index(['parent_id', 'order']);
            $table->index(['is_active', 'parent_id']);
            $table->index(['permission_name', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
