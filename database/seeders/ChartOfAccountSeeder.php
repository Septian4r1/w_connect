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
        | ASSET
        |=========================================================
        */

        $asset = ChartOfAccount::create([
            'code' => '1000',
            'name' => 'Asset',
            'type' => 'asset',
            'normal_balance' => 'debit',
            'is_header' => true,
            'level' => 1,
            'sort_order' => 1,
        ]);

        $currentAsset = ChartOfAccount::create([
            'parent_id' => $asset->id,
            'code' => '1100',
            'name' => 'Current Asset',
            'type' => 'asset',
            'normal_balance' => 'debit',
            'is_header' => true,
            'level' => 2,
            'sort_order' => 1,
        ]);

        $cashBank = ChartOfAccount::create([
            'parent_id' => $currentAsset->id,
            'code' => '1110',
            'name' => 'Cash & Bank',
            'type' => 'asset',
            'normal_balance' => 'debit',
            'is_header' => true,
            'level' => 3,
            'sort_order' => 1,
        ]);

        ChartOfAccount::create([
            'parent_id' => $cashBank->id,
            'code' => '1111',
            'name' => 'Kas RW',
            'type' => 'asset',
            'normal_balance' => 'debit',
            'is_header' => false,
            'level' => 4,
            'sort_order' => 1,
        ]);

        ChartOfAccount::create([
            'parent_id' => $cashBank->id,
            'code' => '1112',
            'name' => 'Bank RW',
            'type' => 'asset',
            'normal_balance' => 'debit',
            'is_header' => false,
            'level' => 4,
            'sort_order' => 2,
        ]);

        /*
        |=========================================================
        | LIABILITY
        |=========================================================
        */

        $liability = ChartOfAccount::create([
            'code' => '2000',
            'name' => 'Liability',
            'type' => 'liability',
            'normal_balance' => 'credit',
            'is_header' => true,
            'level' => 1,
            'sort_order' => 2,
        ]);

        $currentLiability = ChartOfAccount::create([
            'parent_id' => $liability->id,
            'code' => '2100',
            'name' => 'Current Liability',
            'type' => 'liability',
            'normal_balance' => 'credit',
            'is_header' => true,
            'level' => 2,
            'sort_order' => 1,
        ]);

        ChartOfAccount::create([
            'parent_id' => $currentLiability->id,
            'code' => '2110',
            'name' => 'Hutang Operasional',
            'type' => 'liability',
            'normal_balance' => 'credit',
            'is_header' => false,
            'level' => 3,
            'sort_order' => 1,
        ]);

        /*
        |=========================================================
        | EQUITY
        |=========================================================
        */

        $equity = ChartOfAccount::create([
            'code' => '3000',
            'name' => 'Equity',
            'type' => 'equity',
            'normal_balance' => 'credit',
            'is_header' => true,
            'level' => 1,
            'sort_order' => 3,
        ]);

        ChartOfAccount::create([
            'parent_id' => $equity->id,
            'code' => '3100',
            'name' => 'Modal RW',
            'type' => 'equity',
            'normal_balance' => 'credit',
            'is_header' => false,
            'level' => 2,
            'sort_order' => 1,
        ]);

        /*
        |=========================================================
        | REVENUE
        |=========================================================
        */

        $revenue = ChartOfAccount::create([
            'code' => '4000',
            'name' => 'Revenue',
            'type' => 'revenue',
            'normal_balance' => 'credit',
            'is_header' => true,
            'level' => 1,
            'sort_order' => 4,
        ]);

        ChartOfAccount::create([
            'parent_id' => $revenue->id,
            'code' => '4100',
            'name' => 'Iuran Warga',
            'type' => 'revenue',
            'normal_balance' => 'credit',
            'is_header' => false,
            'level' => 2,
            'sort_order' => 1,
        ]);

        ChartOfAccount::create([
            'parent_id' => $revenue->id,
            'code' => '4200',
            'name' => 'Pendapatan Lain-lain',
            'type' => 'revenue',
            'normal_balance' => 'credit',
            'is_header' => false,
            'level' => 2,
            'sort_order' => 2,
        ]);

        /*
        |=========================================================
        | EXPENSE
        |=========================================================
        */

        $expense = ChartOfAccount::create([
            'code' => '5000',
            'name' => 'Expense',
            'type' => 'expense',
            'normal_balance' => 'debit',
            'is_header' => true,
            'level' => 1,
            'sort_order' => 5,
        ]);

        $operationalExpense = ChartOfAccount::create([
            'parent_id' => $expense->id,
            'code' => '5100',
            'name' => 'Operational Expense',
            'type' => 'expense',
            'normal_balance' => 'debit',
            'is_header' => true,
            'level' => 2,
            'sort_order' => 1,
        ]);

        $utility = ChartOfAccount::create([
            'parent_id' => $operationalExpense->id,
            'code' => '5110',
            'name' => 'Utilities',
            'type' => 'expense',
            'normal_balance' => 'debit',
            'is_header' => true,
            'level' => 3,
            'sort_order' => 1,
        ]);

        ChartOfAccount::create([
            'parent_id' => $utility->id,
            'code' => '5111',
            'name' => 'Listrik',
            'type' => 'expense',
            'normal_balance' => 'debit',
            'is_header' => false,
            'level' => 4,
            'sort_order' => 1,
        ]);

        ChartOfAccount::create([
            'parent_id' => $utility->id,
            'code' => '5112',
            'name' => 'Air / PDAM',
            'type' => 'expense',
            'normal_balance' => 'debit',
            'is_header' => false,
            'level' => 4,
            'sort_order' => 2,
        ]);

        $adminExpense = ChartOfAccount::create([
            'parent_id' => $operationalExpense->id,
            'code' => '5120',
            'name' => 'Administrasi',
            'type' => 'expense',
            'normal_balance' => 'debit',
            'is_header' => true,
            'level' => 3,
            'sort_order' => 2,
        ]);

        ChartOfAccount::create([
            'parent_id' => $adminExpense->id,
            'code' => '5121',
            'name' => 'ATK',
            'type' => 'expense',
            'normal_balance' => 'debit',
            'is_header' => false,
            'level' => 4,
            'sort_order' => 1,
        ]);

        ChartOfAccount::create([
            'parent_id' => $adminExpense->id,
            'code' => '5122',
            'name' => 'Operasional Kantor',
            'type' => 'expense',
            'normal_balance' => 'debit',
            'is_header' => false,
            'level' => 4,
            'sort_order' => 2,
        ]);
    }
}
