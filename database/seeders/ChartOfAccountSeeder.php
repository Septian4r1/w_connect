<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Accounting\ChartOfAccount;

class ChartOfAccountSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |=========================================================
        | RESET (DEV ONLY)
        |=========================================================
        */
        ChartOfAccount::truncate();

        /*
        |=========================================================
        | 1. ASSET
        |=========================================================
        */

        $asset = ChartOfAccount::create([
            'code' => '1000',
            'name' => 'Asset',
            'type' => 'asset',
            'normal_balance' => 'debit',
            'is_header' => 1,
            'is_postable' => 0,
            'level' => 1,
            'sort_order' => 1,
        ]);

        $currentAsset = ChartOfAccount::create([
            'parent_id' => $asset->id,
            'code' => '1100',
            'name' => 'Current Asset',
            'type' => 'asset',
            'normal_balance' => 'debit',
            'is_header' => 1,
            'is_postable' => 0,
            'level' => 2,
            'sort_order' => 1,
        ]);

        /*
        |-------------------------------
        | CASH & BANK
        |-------------------------------
        */

        $cashBank = ChartOfAccount::create([
            'parent_id' => $currentAsset->id,
            'code' => '1110',
            'name' => 'Cash & Bank',
            'type' => 'asset',
            'normal_balance' => 'debit',
            'is_header' => 1,
            'is_postable' => 0,
            'level' => 3,
            'sort_order' => 1,
        ]);

        ChartOfAccount::create([
            'parent_id' => $cashBank->id,
            'code' => '1111',
            'name' => 'Cash RW',
            'type' => 'asset',
            'normal_balance' => 'debit',
            'is_header' => 0,
            'is_postable' => 1,
            'level' => 4,
            'sort_order' => 1,
        ]);

        ChartOfAccount::create([
            'parent_id' => $cashBank->id,
            'code' => '1112',
            'name' => 'Bank RW',
            'type' => 'asset',
            'normal_balance' => 'debit',
            'is_header' => 0,
            'is_postable' => 1,
            'level' => 4,
            'sort_order' => 2,
        ]);

        ChartOfAccount::create([
            'parent_id' => $cashBank->id,
            'code' => '1113',
            'name' => 'Petty Cash',
            'type' => 'asset',
            'normal_balance' => 'debit',
            'is_header' => 0,
            'is_postable' => 1,
            'level' => 4,
            'sort_order' => 3,
        ]);

        /*
        |-------------------------------
        | RECEIVABLE (IFRS ADDITION)
        |-------------------------------
        */

        $receivable = ChartOfAccount::create([
            'parent_id' => $currentAsset->id,
            'code' => '1200',
            'name' => 'Receivable',
            'type' => 'asset',
            'normal_balance' => 'debit',
            'is_header' => 1,
            'is_postable' => 0,
            'level' => 3,
            'sort_order' => 2,
        ]);

        ChartOfAccount::create([
            'parent_id' => $receivable->id,
            'code' => '1210',
            'name' => 'Piutang Iuran Warga',
            'type' => 'asset',
            'normal_balance' => 'debit',
            'is_header' => 0,
            'is_postable' => 1,
            'level' => 4,
            'sort_order' => 1,
        ]);

        /*
        |=========================================================
        | 2. LIABILITY
        |=========================================================
        */

        $liability = ChartOfAccount::create([
            'code' => '2000',
            'name' => 'Liability',
            'type' => 'liability',
            'normal_balance' => 'credit',
            'is_header' => 1,
            'is_postable' => 0,
            'level' => 1,
            'sort_order' => 2,
        ]);

        $currentLiability = ChartOfAccount::create([
            'parent_id' => $liability->id,
            'code' => '2100',
            'name' => 'Current Liability',
            'type' => 'liability',
            'normal_balance' => 'credit',
            'is_header' => 1,
            'is_postable' => 0,
            'level' => 2,
            'sort_order' => 1,
        ]);

        ChartOfAccount::create([
            'parent_id' => $currentLiability->id,
            'code' => '2110',
            'name' => 'Hutang Operasional',
            'type' => 'liability',
            'normal_balance' => 'credit',
            'is_header' => 0,
            'is_postable' => 1,
            'level' => 3,
            'sort_order' => 1,
        ]);

        ChartOfAccount::create([
            'parent_id' => $currentLiability->id,
            'code' => '2120',
            'name' => 'Hutang Kegiatan RW',
            'type' => 'liability',
            'normal_balance' => 'credit',
            'is_header' => 0,
            'is_postable' => 1,
            'level' => 3,
            'sort_order' => 2,
        ]);

        /*
        |=========================================================
        | 3. EQUITY (IFRS FIXED)
        |=========================================================
        */

        $equity = ChartOfAccount::create([
            'code' => '3000',
            'name' => 'Equity',
            'type' => 'equity',
            'normal_balance' => 'credit',
            'is_header' => 1,
            'is_postable' => 0,
            'level' => 1,
            'sort_order' => 3,
        ]);

        ChartOfAccount::create([
            'parent_id' => $equity->id,
            'code' => '3100',
            'name' => 'Modal RW',
            'type' => 'equity',
            'normal_balance' => 'credit',
            'is_header' => 0,
            'is_postable' => 1,
            'level' => 2,
            'sort_order' => 1,
        ]);

        ChartOfAccount::create([
            'parent_id' => $equity->id,
            'code' => '3200',
            'name' => 'Retained Earnings (Laba Ditahan)',
            'type' => 'equity',
            'normal_balance' => 'credit',
            'is_header' => 0,
            'is_postable' => 1,
            'level' => 2,
            'sort_order' => 2,
        ]);

        /*
        |=========================================================
        | 4. REVENUE
        |=========================================================
        */

        $revenue = ChartOfAccount::create([
            'code' => '4000',
            'name' => 'Revenue',
            'type' => 'revenue',
            'normal_balance' => 'credit',
            'is_header' => 1,
            'is_postable' => 0,
            'level' => 1,
            'sort_order' => 4,
        ]);

        $iuran = ChartOfAccount::create([
            'parent_id' => $revenue->id,
            'code' => '4100',
            'name' => 'Iuran Warga',
            'type' => 'revenue',
            'normal_balance' => 'credit',
            'is_header' => 1,
            'is_postable' => 0,
            'level' => 2,
            'sort_order' => 1,
        ]);

        ChartOfAccount::create([
            'parent_id' => $iuran->id,
            'code' => '4110',
            'name' => 'Iuran Bulanan',
            'type' => 'revenue',
            'normal_balance' => 'credit',
            'is_header' => 0,
            'is_postable' => 1,
            'level' => 3,
            'sort_order' => 1,
        ]);

        ChartOfAccount::create([
            'parent_id' => $iuran->id,
            'code' => '4120',
            'name' => 'Iuran Tahunan',
            'type' => 'revenue',
            'normal_balance' => 'credit',
            'is_header' => 0,
            'is_postable' => 1,
            'level' => 3,
            'sort_order' => 2,
        ]);

        /*
        |=========================================================
        | 5. EXPENSE
        |=========================================================
        */

        $expense = ChartOfAccount::create([
            'code' => '5000',
            'name' => 'Expense',
            'type' => 'expense',
            'normal_balance' => 'debit',
            'is_header' => 1,
            'is_postable' => 0,
            'level' => 1,
            'sort_order' => 5,
        ]);

        $operational = ChartOfAccount::create([
            'parent_id' => $expense->id,
            'code' => '5100',
            'name' => 'Operational Expense',
            'type' => 'expense',
            'normal_balance' => 'debit',
            'is_header' => 1,
            'is_postable' => 0,
            'level' => 2,
            'sort_order' => 1,
        ]);

        ChartOfAccount::create([
            'parent_id' => $operational->id,
            'code' => '5110',
            'name' => 'Listrik',
            'type' => 'expense',
            'normal_balance' => 'debit',
            'is_header' => 0,
            'is_postable' => 1,
            'level' => 3,
            'sort_order' => 1,
        ]);

        ChartOfAccount::create([
            'parent_id' => $operational->id,
            'code' => '5120',
            'name' => 'Air / PDAM',
            'type' => 'expense',
            'normal_balance' => 'debit',
            'is_header' => 0,
            'is_postable' => 1,
            'level' => 3,
            'sort_order' => 2,
        ]);

        ChartOfAccount::create([
            'parent_id' => $operational->id,
            'code' => '5130',
            'name' => 'ATK',
            'type' => 'expense',
            'normal_balance' => 'debit',
            'is_header' => 0,
            'is_postable' => 1,
            'level' => 3,
            'sort_order' => 3,
        ]);

        ChartOfAccount::create([
            'parent_id' => $operational->id,
            'code' => '5140',
            'name' => 'Kegiatan RW',
            'type' => 'expense',
            'normal_balance' => 'debit',
            'is_header' => 0,
            'is_postable' => 1,
            'level' => 3,
            'sort_order' => 4,
        ]);
    }
}
