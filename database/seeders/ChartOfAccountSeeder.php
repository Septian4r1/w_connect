<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Accounting\ChartOfAccount;

class ChartOfAccountSeeder extends Seeder
{
    public function run(): void
    {
        ChartOfAccount::truncate();

        $create = function (array $data) {
            return ChartOfAccount::create(array_merge([
                'parent_id'       => null,
                'opening_balance' => 0,
                'currency'        => 'IDR',
                'description'     => null,
                'is_header'       => 0,
                'is_postable'     => 1,
                'is_active'       => 1,
            ], $data));
        };

        /*
        |-------------------------
        | 1. ASSET
        |-------------------------
        */
        $asset = $create([
            'code' => '1000',
            'name' => 'ASSET',
            'type' => 'asset',
            'normal_balance' => 'debit',
            'is_header' => 1,
            'is_postable' => 0,
            'level' => 1,
            'sort_order' => 1,
        ]);

        $currentAsset = $create([
            'parent_id' => $asset->id,
            'code' => '1100',
            'name' => 'CURRENT ASSET',
            'type' => 'asset',
            'normal_balance' => 'debit',
            'is_header' => 1,
            'is_postable' => 0,
            'level' => 2,
            'sort_order' => 1,
        ]);

        $cashBank = $create([
            'parent_id' => $currentAsset->id,
            'code' => '1110',
            'name' => 'CASH & BANK',
            'type' => 'asset',
            'normal_balance' => 'debit',
            'is_header' => 1,
            'is_postable' => 0,
            'level' => 3,
            'sort_order' => 1,
        ]);

        $cashAccounts = [
            ['1111', 'Kas RW'],
            ['1112', 'Bank BJB'],
            ['1113', 'Petty Cash'],
            ['1114', 'Bank BCA'],
            ['1115', 'Kas Petugas RT'],
        ];

        foreach ($cashAccounts as $i => $a) {
            $create([
                'parent_id' => $cashBank->id,
                'code' => $a[0],
                'name' => $a[1],
                'type' => 'asset',
                'normal_balance' => 'debit',
                'level' => 4,
                'sort_order' => $i + 1,
            ]);
        }

        $restrictedCash = $create([
            'parent_id' => $currentAsset->id,
            'code' => '1120',
            'name' => 'RESTRICTED CASH FUND',
            'type' => 'asset',
            'normal_balance' => 'debit',
            'is_header' => 1,
            'level' => 3,
            'sort_order' => 2,
        ]);

        $restrictedFunds = [
            ['1121', 'Kas Dana RT'],
            ['1122', 'Kas Dana RW'],
            ['1123', 'Kas Dana Sampah'],
            ['1124', 'Kas Dana Sosial'],
            ['1125', 'Kas Dana Infrastruktur'],
            ['1126', 'Kas Dana Rukem'],
        ];

        foreach ($restrictedFunds as $i => $a) {
            $create([
                'parent_id' => $restrictedCash->id,
                'code' => $a[0],
                'name' => $a[1],
                'type' => 'asset',
                'normal_balance' => 'debit',
                'level' => 4,
                'sort_order' => $i + 1,
            ]);
        }

        $receivable = $create([
            'parent_id' => $currentAsset->id,
            'code' => '1200',
            'name' => 'RECEIVABLE',
            'type' => 'asset',
            'normal_balance' => 'debit',
            'is_header' => 1,
        ]);

        $receivables = [
            ['1210', 'Piutang IPL Warga'],
            ['1220', 'Piutang Denda'],
            ['1230', 'Piutang Lain-lain'],
            ['1240', 'Cadangan Piutang Tak Tertagih'],
        ];

        foreach ($receivables as $i => $a) {
            $create([
                'parent_id' => $receivable->id,
                'code' => $a[0],
                'name' => $a[1],
                'type' => 'asset',
                'normal_balance' => 'debit',
                'level' => 4,
                'sort_order' => $i + 1,
            ]);
        }

        $fixedAsset = $create([
            'parent_id' => $asset->id,
            'code' => '1300',
            'name' => 'FIXED ASSET',
            'type' => 'asset',
        ]);

        $fixedAssets = [
            ['1310', 'Peralatan RW'],
            ['1320', 'Inventaris Pos Security'],
            ['1330', 'Aset Infrastruktur'],
        ];

        foreach ($fixedAssets as $i => $a) {
            $create([
                'parent_id' => $fixedAsset->id,
                'code' => $a[0],
                'name' => $a[1],
            ]);
        }

        /*
        |-------------------------
        | LIABILITY
        |-------------------------
        */
        $liability = $create([
            'code' => '2000',
            'name' => 'LIABILITY',
            'type' => 'liability',
            'normal_balance' => 'credit',
            'is_header' => 1,
        ]);

        $currentLiability = $create([
            'parent_id' => $liability->id,
            'code' => '2100',
            'name' => 'CURRENT LIABILITY',
            'type' => 'liability',
        ]);

        $liabilities = [
            ['2110', 'Hutang Operasional'],
            ['2120', 'Hutang Vendor'],
            ['2130', 'Hutang Kegiatan'],
            ['2140', 'Titipan IPL Petugas'],
            ['2150', 'Hutang Settlement RW'],
            ['2160', 'Hutang Settlement Vendor'],
        ];

        foreach ($liabilities as $i => $a) {
            $create([
                'parent_id' => $currentLiability->id,
                'code' => $a[0],
                'name' => $a[1],
                'type' => 'liability',
                'normal_balance' => 'credit',
            ]);
        }

        $fundLiability = $create([
            'parent_id' => $currentLiability->id,
            'code' => '2200',
            'name' => 'TITIPAN DANA WARGA',
            'type' => 'liability',
        ]);

        $funds = [
            ['2210', 'Dana RT'],
            ['2220', 'Dana RW'],
            ['2230', 'Dana Sampah'],
            ['2240', 'Dana Sosial'],
            ['2250', 'Dana Infrastruktur'],
            ['2260', 'Dana Rukem'],
        ];

        foreach ($funds as $i => $a) {
            $create([
                'parent_id' => $fundLiability->id,
                'code' => $a[0],
                'name' => $a[1],
                'type' => 'liability',
                'normal_balance' => 'credit',
            ]);
        }

        /*
        |-------------------------
        | EQUITY
        |-------------------------
        */
        $equity = $create([
            'code' => '3000',
            'name' => 'EQUITY',
            'type' => 'equity',
            'normal_balance' => 'credit',
            'is_header' => 1,
        ]);

        $equities = [
            ['3100', 'Modal Awal RW'],
            ['3200', 'Saldo Laba Ditahan'],
        ];

        foreach ($equities as $a) {
            $create([
                'parent_id' => $equity->id,
                'code' => $a[0],
                'name' => $a[1],
                'type' => 'equity',
                'normal_balance' => 'credit',
            ]);
        }

        /*
        |-------------------------
        | REVENUE
        |-------------------------
        */
        $revenue = $create([
            'code' => '4000',
            'name' => 'REVENUE',
            'type' => 'revenue',
            'normal_balance' => 'credit',
            'is_header' => 1,
        ]);

        $revenues = [
            ['4110', 'Pendapatan Administrasi'],
            ['4120', 'Pendapatan Denda'],
            ['4130', 'Donasi Bebas'],
            ['4140', 'Pendapatan Lain-lain'],
            ['4150', 'Penerimaan IPL'],
        ];

        foreach ($revenues as $a) {
            $create([
                'parent_id' => $revenue->id,
                'code' => $a[0],
                'name' => $a[1],
                'type' => 'revenue',
                'normal_balance' => 'credit',
            ]);
        }

        /*
        |-------------------------
        | EXPENSE
        |-------------------------
        */
        $expense = $create([
            'code' => '5000',
            'name' => 'EXPENSE',
            'type' => 'expense',
            'normal_balance' => 'debit',
            'is_header' => 1,
        ]);

        $operational = $create([
            'parent_id' => $expense->id,
            'code' => '5100',
            'name' => 'OPERATIONAL EXPENSE',
            'type' => 'expense',
        ]);

        $expenses = [
            ['5110', 'Listrik'],
            ['5120', 'Air / PDAM'],
            ['5130', 'ATK'],
            ['5140', 'Kegiatan RW'],
            ['5150', 'Kebersihan & Sampah'],
            ['5160', 'Keamanan'],
            ['5170', 'Perawatan Infrastruktur'],
            ['5180', 'Santunan Sosial'],
            ['5190', 'Biaya Rukem'],
            ['5200', 'Kegiatan RT'],
            ['5210', 'Beban Piutang Tak Tertagih'],
        ];

        foreach ($expenses as $a) {
            $create([
                'parent_id' => $operational->id,
                'code' => $a[0],
                'name' => $a[1],
                'type' => 'expense',
                'normal_balance' => 'debit',
            ]);
        }
    }
}
