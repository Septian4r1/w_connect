<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fund_account_links', function (Blueprint $table) {

            /*
            |----------------------------------------------------------
            | ADD NEW SCOPE STRUCTURE
            |----------------------------------------------------------
            */

            $table->string('scope_type')
                ->nullable()
                ->after('account_role_id')
                ->index();

            $table->unsignedBigInteger('scope_id')
                ->nullable()
                ->after('scope_type')
                ->index();

            /*
            |----------------------------------------------------------
            | OPTIONAL: KEEP OLD COLUMN FOR SAFETY (TEMP)
            |----------------------------------------------------------
            */
            // jangan langsung drop dulu biar aman
            // $table->dropColumn('scope');
        });
    }

    public function down(): void
    {
        Schema::table('fund_account_links', function (Blueprint $table) {

            /*
            |----------------------------------------------------------
            | DROP NEW STRUCTURE
            |----------------------------------------------------------
            */

            $table->dropIndex(['scope_type']);
            $table->dropIndex(['scope_id']);

            $table->dropColumn(['scope_type', 'scope_id']);

            /*
            |----------------------------------------------------------
            | RESTORE OLD COLUMN
            |----------------------------------------------------------
            */

            $table->string('scope')->nullable();
        });
    }
};
