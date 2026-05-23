<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fund_account_links', function (Blueprint $table) {

            $table->id();

            /*
            |----------------------------------------
            | FUND TYPE
            |----------------------------------------
            */
            $table->foreignId('fund_type_id')
                ->constrained('fund_types')
                ->cascadeOnDelete();

            /*
            |----------------------------------------
            | COA (CHART OF ACCOUNT)
            |----------------------------------------
            */
            $table->foreignId('coa_id')
                ->constrained('chart_of_accounts')
                ->cascadeOnDelete();

            /*
            |----------------------------------------
            | ACCOUNT ROLE (ERP CORE)
            |----------------------------------------
            | cash | bank | revenue | expense | payable | receivable
            */
            $table->foreignId('account_role_id')
                ->constrained('account_roles')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            /*
            |----------------------------------------
            | SCOPE SYSTEM (RW / RT / GLOBAL)
            |----------------------------------------
            */
            $table->string('scope_type')->default('rw'); // rw | rt | global
            $table->unsignedBigInteger('scope_id')->nullable(); // id RW/RT

            /*
            |----------------------------------------
            | RULE ENGINE
            |----------------------------------------
            */
            $table->integer('priority')->default(0);

            /*
            |----------------------------------------
            | FLAGS
            |----------------------------------------
            */
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            /*
            |----------------------------------------
            | INDEXES (IMPORTANT FOR ERP PERFORMANCE)
            |----------------------------------------
            */
            $table->index(['fund_type_id', 'account_role_id']);
            $table->index(['scope_type', 'scope_id']);
            $table->index(['coa_id']);

            /*
            |----------------------------------------
            | UNIQUE RULE (ANTI DUPLICATE CONFIG)
            |----------------------------------------
            */
            $table->unique(
                [
                    'fund_type_id',
                    'account_role_id',
                    'scope_type',
                    'scope_id',
                    'is_default'
                ],
                'fund_account_scope_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fund_account_links');
    }
};
