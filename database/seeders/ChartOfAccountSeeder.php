<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Accounting\ChartOfAccount;

class ChartOfAccountSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | SAFE RESET
        |--------------------------------------------------------------------------
        */

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('chart_of_accounts')->delete();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        /*
        |--------------------------------------------------------------------------
        | HELPER : BUILD PARENT PATH
        |--------------------------------------------------------------------------
        */

        $buildPath = function ($parentId) {

            if (!$parentId) {
                return null;
            }

            $parent = ChartOfAccount::find($parentId);

            if (!$parent) {
                return null;
            }

            return $parent->parent_path
                ? $parent->parent_path . '/' . $parent->id
                : (string) $parent->id;
        };

        /*
        |--------------------------------------------------------------------------
        | HELPER : CREATE ACCOUNT
        |--------------------------------------------------------------------------
        */

        $create = function (array $data) use ($buildPath) {

            $data['parent_path'] = isset($data['parent_id'])
                ? $buildPath($data['parent_id'])
                : null;

            return ChartOfAccount::create(array_merge([
                'parent_id'       => null,
                'opening_balance' => 0,
                'currency'        => 'IDR',
                'description'     => null,

                'is_header'       => false,
                'is_postable'     => true,
                'is_active'       => true,

                'sort_order'      => 1,
            ], $data));
        };

        /*
        |--------------------------------------------------------------------------
        | 1. ASSET
        |--------------------------------------------------------------------------
        */

        $asset = $create([
            'code'             => '1000',
            'name'             => 'ASSET',
            'type'             => 'asset',
            'normal_balance'   => 'debit',
            'level'            => 1,
            'sort_order'       => 1,
            'is_header'        => true,
            'is_postable'      => false,
        ]);

        /*
        |--------------------------------------------------------------------------
        | CURRENT ASSET
        |--------------------------------------------------------------------------
        */

        $currentAsset = $create([
            'parent_id'        => $asset->id,
            'code'             => '1100',
            'name'             => 'CURRENT ASSET',
            'type'             => 'asset',
            'normal_balance'   => 'debit',
            'level'            => 2,
            'sort_order'       => 1,
            'is_header'        => true,
            'is_postable'      => false,
        ]);

        /*
        |--------------------------------------------------------------------------
        | CASH & BANK
        |--------------------------------------------------------------------------
        */

        $cashBank = $create([
            'parent_id'        => $currentAsset->id,
            'code'             => '1110',
            'name'             => 'CASH & BANK',
            'type'             => 'asset',
            'normal_balance'   => 'debit',
            'level'            => 3,
            'sort_order'       => 1,
            'is_header'        => true,
            'is_postable'      => false,
        ]);

        $cashAccounts = [
            ['1111', 'Kas RW'],
            ['1112', 'Bank BJB'],
            ['1113', 'Petty Cash'],
            ['1114', 'Bank BCA'],
            ['1115', 'Kas Petugas RT'],
        ];

        foreach ($cashAccounts as $i => $account) {

            $create([
                'parent_id'      => $cashBank->id,
                'code'           => $account[0],
                'name'           => $account[1],
                'type'           => 'asset',
                'normal_balance' => 'debit',
                'level'          => 4,
                'sort_order'     => $i + 1,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | RESTRICTED CASH FUND
        |--------------------------------------------------------------------------
        */

        $restrictedCash = $create([
            'parent_id'        => $currentAsset->id,
            'code'             => '1120',
            'name'             => 'RESTRICTED CASH FUND',
            'type'             => 'asset',
            'normal_balance'   => 'debit',
            'level'            => 3,
            'sort_order'       => 2,
            'is_header'        => true,
            'is_postable'      => false,
        ]);

        $restrictedFunds = [
            ['1121', 'Kas Dana RT'],
            ['1122', 'Kas Dana RW'],
            ['1123', 'Kas Dana Sampah'],
            ['1124', 'Kas Dana Sosial'],
            ['1125', 'Kas Dana Infrastruktur'],
            ['1126', 'Kas Dana Rukem'],
        ];

        foreach ($restrictedFunds as $i => $account) {

            $create([
                'parent_id'      => $restrictedCash->id,
                'code'           => $account[0],
                'name'           => $account[1],
                'type'           => 'asset',
                'normal_balance' => 'debit',
                'level'          => 4,
                'sort_order'     => $i + 1,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | RECEIVABLE
        |--------------------------------------------------------------------------
        */

        $receivable = $create([
            'parent_id'        => $currentAsset->id,
            'code'             => '1200',
            'name'             => 'RECEIVABLE',
            'type'             => 'asset',
            'normal_balance'   => 'debit',
            'level'            => 3,
            'sort_order'       => 3,
            'is_header'        => true,
            'is_postable'      => false,
        ]);

        $receivables = [
            ['1210', 'Piutang IPL Warga', 'debit'],
            ['1220', 'Piutang Denda', 'debit'],
            ['1230', 'Piutang Lain-lain', 'debit'],
            ['1240', 'Cadangan Piutang Tak Tertagih', 'credit'],
        ];

        foreach ($receivables as $i => $account) {

            $create([
                'parent_id'      => $receivable->id,
                'code'           => $account[0],
                'name'           => $account[1],
                'type'           => 'asset',
                'normal_balance' => $account[2],
                'level'          => 4,
                'sort_order'     => $i + 1,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | FIXED ASSET
        |--------------------------------------------------------------------------
        */

        $fixedAsset = $create([
            'parent_id'        => $asset->id,
            'code'             => '1300',
            'name'             => 'FIXED ASSET',
            'type'             => 'asset',
            'normal_balance'   => 'debit',
            'level'            => 2,
            'sort_order'       => 2,
            'is_header'        => true,
            'is_postable'      => false,
        ]);

        $fixedAssets = [
            ['1310', 'Peralatan RW'],
            ['1320', 'Inventaris Pos Security'],
            ['1330', 'Aset Infrastruktur'],
        ];

        foreach ($fixedAssets as $i => $account) {

            $create([
                'parent_id'      => $fixedAsset->id,
                'code'           => $account[0],
                'name'           => $account[1],
                'type'           => 'asset',
                'normal_balance' => 'debit',
                'level'          => 3,
                'sort_order'     => $i + 1,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | 2. LIABILITY
        |--------------------------------------------------------------------------
        */

        $liability = $create([
            'code'             => '2000',
            'name'             => 'LIABILITY',
            'type'             => 'liability',
            'normal_balance'   => 'credit',
            'level'            => 1,
            'sort_order'       => 2,
            'is_header'        => true,
            'is_postable'      => false,
        ]);

        $currentLiability = $create([
            'parent_id'        => $liability->id,
            'code'             => '2100',
            'name'             => 'CURRENT LIABILITY',
            'type'             => 'liability',
            'normal_balance'   => 'credit',
            'level'            => 2,
            'sort_order'       => 1,
            'is_header'        => true,
            'is_postable'      => false,
        ]);

        $liabilities = [
            ['2110', 'Hutang Operasional'],
            ['2120', 'Hutang Vendor'],
            ['2130', 'Hutang Kegiatan'],
            ['2140', 'Titipan IPL Petugas'],
            ['2150', 'Hutang Settlement RW'],
            ['2160', 'Hutang Settlement Vendor'],
        ];

        foreach ($liabilities as $i => $account) {

            $create([
                'parent_id'      => $currentLiability->id,
                'code'           => $account[0],
                'name'           => $account[1],
                'type'           => 'liability',
                'normal_balance' => 'credit',
                'level'          => 3,
                'sort_order'     => $i + 1,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | TITIPAN DANA WARGA
        |--------------------------------------------------------------------------
        */

        $fundLiability = $create([
            'parent_id'        => $currentLiability->id,
            'code'             => '2200',
            'name'             => 'TITIPAN DANA WARGA',
            'type'             => 'liability',
            'normal_balance'   => 'credit',
            'level'            => 3,
            'sort_order'       => 2,
            'is_header'        => true,
            'is_postable'      => false,
        ]);

        $funds = [
            ['2210', 'Dana RT'],
            ['2220', 'Dana RW'],
            ['2230', 'Dana Sampah'],
            ['2240', 'Dana Sosial'],
            ['2250', 'Dana Infrastruktur'],
            ['2260', 'Dana Rukem'],
        ];

        foreach ($funds as $i => $account) {

            $create([
                'parent_id'      => $fundLiability->id,
                'code'           => $account[0],
                'name'           => $account[1],
                'type'           => 'liability',
                'normal_balance' => 'credit',
                'level'          => 4,
                'sort_order'     => $i + 1,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | 3. EQUITY
        |--------------------------------------------------------------------------
        */

        $equity = $create([
            'code'             => '3000',
            'name'             => 'EQUITY',
            'type'             => 'equity',
            'normal_balance'   => 'credit',
            'level'            => 1,
            'sort_order'       => 3,
            'is_header'        => true,
            'is_postable'      => false,
        ]);

        $equities = [
            ['3100', 'Modal Awal RW'],
            ['3200', 'Saldo Laba Ditahan'],
        ];

        foreach ($equities as $i => $account) {

            $create([
                'parent_id'      => $equity->id,
                'code'           => $account[0],
                'name'           => $account[1],
                'type'           => 'equity',
                'normal_balance' => 'credit',
                'level'          => 2,
                'sort_order'     => $i + 1,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | 4. REVENUE
        |--------------------------------------------------------------------------
        */

        $revenue = $create([
            'code'             => '4000',
            'name'             => 'REVENUE',
            'type'             => 'revenue',
            'normal_balance'   => 'credit',
            'level'            => 1,
            'sort_order'       => 4,
            'is_header'        => true,
            'is_postable'      => false,
        ]);

        $revenues = [
            ['4110', 'Pendapatan IPL'],
            ['4120', 'Pendapatan Administrasi'],
            ['4130', 'Pendapatan Denda'],
            ['4140', 'Donasi Bebas'],
            ['4150', 'Pendapatan Lain-lain'],

            // DETAIL FUND REVENUE
            ['4160', 'Pendapatan Dana Infrastruktur'],
            ['4170', 'Pendapatan Dana Sampah'],
            ['4180', 'Pendapatan Dana Rukem'],
        ];

        foreach ($revenues as $i => $account) {

            $create([
                'parent_id'      => $revenue->id,
                'code'           => $account[0],
                'name'           => $account[1],
                'type'           => 'revenue',
                'normal_balance' => 'credit',
                'level'          => 2,
                'sort_order'     => $i + 1,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | 5. EXPENSE
        |--------------------------------------------------------------------------
        */

        $expense = $create([
            'code'             => '5000',
            'name'             => 'EXPENSE',
            'type'             => 'expense',
            'normal_balance'   => 'debit',
            'level'            => 1,
            'sort_order'       => 5,
            'is_header'        => true,
            'is_postable'      => false,
        ]);

        $operational = $create([
            'parent_id'        => $expense->id,
            'code'             => '5100',
            'name'             => 'OPERATIONAL EXPENSE',
            'type'             => 'expense',
            'normal_balance'   => 'debit',
            'level'            => 2,
            'sort_order'       => 1,
            'is_header'        => true,
            'is_postable'      => false,
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

        foreach ($expenses as $i => $account) {

            $create([
                'parent_id'      => $operational->id,
                'code'           => $account[0],
                'name'           => $account[1],
                'type'           => 'expense',
                'normal_balance' => 'debit',
                'level'          => 3,
                'sort_order'     => $i + 1,
            ]);
        }
    }
}
