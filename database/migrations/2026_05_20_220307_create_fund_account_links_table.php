<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fund_account_links', function (Blueprint $table) {
            $table->id();

            $table->foreignId('fund_type_id')
                ->constrained('fund_types')
                ->cascadeOnDelete();

            $table->foreignId('coa_id')
                ->constrained('chart_of_accounts')
                ->cascadeOnDelete();

            $table->foreignId('account_role_id')
                ->constrained('account_roles')
                ->cascadeOnDelete();

            $table->foreignId('organization_id')
                ->constrained('organizations')
                ->cascadeOnDelete();

            $table->integer('priority')->default(0);

            $table->boolean('is_default')->default(false);

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique([
                'fund_type_id',
                'organization_id',
                'account_role_id',
                'coa_id'
            ], 'unique_mapping');

            $table->index([
                'fund_type_id',
                'account_role_id'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fund_account_links');
    }
};
