<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE ipl_billing_periods
            MODIFY COLUMN status ENUM(
                'draft',
                'open',
                'generated',
                'closed'
            ) NOT NULL DEFAULT 'draft'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE ipl_billing_periods
            MODIFY COLUMN status ENUM(
                'draft',
                'open',
                'closed',
                'cancelled'
            ) NOT NULL DEFAULT 'draft'
        ");
    }
};
