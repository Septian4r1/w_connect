<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fund_account_links', function (Blueprint $table) {

            // ADD COLUMN SAFELY (CEK DULU)
            if (!Schema::hasColumn('fund_account_links', 'scope_type')) {
                $table->string('scope_type')
                    ->nullable()
                    ->after('account_role_id');
            }

            if (!Schema::hasColumn('fund_account_links', 'scope_id')) {
                $table->unsignedBigInteger('scope_id')
                    ->nullable()
                    ->after('scope_type');
            }

            // INDEX SAFETY (hindari duplicate index error)
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $indexes = array_map(fn($i) => $i->getName(), $sm->listTableIndexes('fund_account_links'));

            if (!in_array('fund_account_links_scope_type_index', $indexes)) {
                $table->index('scope_type');
            }

            if (!in_array('fund_account_links_scope_id_index', $indexes)) {
                $table->index('scope_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('fund_account_links', function (Blueprint $table) {

            if (Schema::hasColumn('fund_account_links', 'scope_type')) {
                $table->dropColumn('scope_type');
            }

            if (Schema::hasColumn('fund_account_links', 'scope_id')) {
                $table->dropColumn('scope_id');
            }
        });
    }
};
