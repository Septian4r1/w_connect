<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Accounting\ChartOfAccount;

class ChartOfAccountSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | RESET (DEV ONLY)
        |--------------------------------------------------------------------------
        */
        ChartOfAccount::truncate();

        /*
        |--------------------------------------------------------------------------
        | HELPER
        |--------------------------------------------------------------------------
        */
        $create = function (array $data) {

            return ChartOfAccount::create(array_merge([
                'parent_id'        => null,
                'opening_balance'  => 0,
                'currency'         => 'IDR',
                'description'      => null,
                'is_header'        => 0,
                'is_postable'      => 1,
                'is_active'        => 1,
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
            'is_header'        => 1,
            'is_postable'      => 0,
            'level'            => 1,
            'sort_order'       => 1,
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
            'is_header'        => 1,
            'is_postable'      => 0,
            'level'            => 2,
            'sort_order'       => 1,
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
            'is_header'        => 1,
            'is_postable'      => 0,
            'level'            => 3,
            'sort_order'       => 1,
        ]);

        /*
        |--------------------------------------------------------------------------
        | KAS OPERASIONAL
        |--------------------------------------------------------------------------
        */

        $cashAccounts = [
            ['1111', 'Kas RW', 'Kas utama operasional RW'],
            ['1112', 'Bank RW', 'Rekening utama RW'],
            ['1113', 'Petty Cash', 'Kas kecil operasional'],
            ['1114', 'Bank BCA', 'Rekening BCA RW'],
            ['1115', 'Kas Petugas RT', 'Kas sementara hasil penagihan IPL'],
        ];

        foreach ($cashAccounts as $index => $cash) {

            $create([
                'parent_id'        => $cashBank->id,
                'code'             => $cash[0],
                'name'             => $cash[1],
                'description'      => $cash[2],
                'type'             => 'asset',
                'normal_balance'   => 'debit',
                'level'            => 4,
                'sort_order'       => $index + 1,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | RESTRICTED CASH (PENTING UNTUK FUND ACCOUNTING)
        |--------------------------------------------------------------------------
        |
        | INI YANG TADI BELUM ADA.
        | JADI BUKAN CUMA LIABILITY DANA.
        | TAPI JUGA ADA PASANGAN ASET/KAS NYA.
        |
        |--------------------------------------------------------------------------
        */

        $restrictedCash = $create([
            'parent_id'        => $currentAsset->id,
            'code'             => '1120',
            'name'             => 'RESTRICTED CASH FUND',
            'type'             => 'asset',
            'normal_balance'   => 'debit',
            'description'      => 'Kas dana khusus yang dibatasi penggunaannya',
            'is_header'        => 1,
            'is_postable'      => 0,
            'level'            => 3,
            'sort_order'       => 2,
        ]);

        /*
        |--------------------------------------------------------------------------
        | PASANGAN ASET UNTUK SETIAP DANA
        |--------------------------------------------------------------------------
        */

        $restrictedFunds = [
            ['1121', 'Kas Dana RT'],
            ['1122', 'Kas Dana RW'],
            ['1123', 'Kas Dana Sampah'],
            ['1124', 'Kas Dana Sosial'],
            ['1125', 'Kas Dana Infrastruktur'],
            ['1126', 'Kas Dana Rukem'],
        ];

        foreach ($restrictedFunds as $index => $fund) {

            $create([
                'parent_id'        => $restrictedCash->id,
                'code'             => $fund[0],
                'name'             => $fund[1],
                'type'             => 'asset',
                'normal_balance'   => 'debit',
                'description'      => 'Kas khusus ' . $fund[1],
                'level'            => 4,
                'sort_order'       => $index + 1,
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
            'is_header'        => 1,
            'is_postable'      => 0,
            'level'            => 3,
            'sort_order'       => 3,
        ]);

        $receivables = [
            ['1210', 'Piutang IPL Warga'],
            ['1220', 'Piutang Denda'],
            ['1230', 'Piutang Lain-lain'],
        ];

        foreach ($receivables as $index => $recv) {

            $create([
                'parent_id'        => $receivable->id,
                'code'             => $recv[0],
                'name'             => $recv[1],
                'type'             => 'asset',
                'normal_balance'   => 'debit',
                'level'            => 4,
                'sort_order'       => $index + 1,
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
            'is_header'        => 1,
            'is_postable'      => 0,
            'level'            => 2,
            'sort_order'       => 2,
        ]);

        $fixedAssets = [
            ['1310', 'Peralatan RW'],
            ['1320', 'Inventaris Pos Security'],
            ['1330', 'Aset Infrastruktur'],
        ];

        foreach ($fixedAssets as $index => $fa) {

            $create([
                'parent_id'        => $fixedAsset->id,
                'code'             => $fa[0],
                'name'             => $fa[1],
                'type'             => 'asset',
                'normal_balance'   => 'debit',
                'level'            => 3,
                'sort_order'       => $index + 1,
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
            'is_header'        => 1,
            'is_postable'      => 0,
            'level'            => 1,
            'sort_order'       => 2,
        ]);

        $currentLiability = $create([
            'parent_id'        => $liability->id,
            'code'             => '2100',
            'name'             => 'CURRENT LIABILITY',
            'type'             => 'liability',
            'normal_balance'   => 'credit',
            'is_header'        => 1,
            'is_postable'      => 0,
            'level'            => 2,
            'sort_order'       => 1,
        ]);

        /*
        |--------------------------------------------------------------------------
        | HUTANG
        |--------------------------------------------------------------------------
        */

        $liabilities = [
            ['2110', 'Hutang Operasional'],
            ['2120', 'Hutang Vendor'],
            ['2130', 'Hutang Kegiatan'],
        ];

        foreach ($liabilities as $index => $liab) {

            $create([
                'parent_id'        => $currentLiability->id,
                'code'             => $liab[0],
                'name'             => $liab[1],
                'type'             => 'liability',
                'normal_balance'   => 'credit',
                'level'            => 3,
                'sort_order'       => $index + 1,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | RESTRICTED FUND LIABILITY
        |--------------------------------------------------------------------------
        |
        | INI KEWAJIBAN TITIPAN DANA
        | BUKAN PENDAPATAN
        |
        |--------------------------------------------------------------------------
        */

        $fundLiability = $create([
            'parent_id'        => $currentLiability->id,
            'code'             => '2200',
            'name'             => 'TITIPAN DANA WARGA',
            'type'             => 'liability',
            'normal_balance'   => 'credit',
            'description'      => 'Dana titipan warga dengan pembatasan penggunaan',
            'is_header'        => 1,
            'is_postable'      => 0,
            'level'            => 3,
            'sort_order'       => 4,
        ]);

        /*
        |--------------------------------------------------------------------------
        | FUND LIABILITY DETAIL
        |--------------------------------------------------------------------------
        */

        $funds = [
            ['2210', 'Dana RT'],
            ['2220', 'Dana RW'],
            ['2230', 'Dana Sampah'],
            ['2240', 'Dana Sosial'],
            ['2250', 'Dana Infrastruktur'],
            ['2260', 'Dana Rukem'],
        ];

        foreach ($funds as $index => $fund) {

            $create([
                'parent_id'        => $fundLiability->id,
                'code'             => $fund[0],
                'name'             => $fund[1],
                'type'             => 'liability',
                'normal_balance'   => 'credit',
                'description'      => 'Saldo titipan ' . $fund[1],
                'level'            => 4,
                'sort_order'       => $index + 1,
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
            'is_header'        => 1,
            'is_postable'      => 0,
            'level'            => 1,
            'sort_order'       => 3,
        ]);

        $equities = [
            ['3100', 'Modal Awal RW'],
            ['3200', 'Saldo Laba Ditahan'],
        ];

        foreach ($equities as $index => $eq) {

            $create([
                'parent_id'        => $equity->id,
                'code'             => $eq[0],
                'name'             => $eq[1],
                'type'             => 'equity',
                'normal_balance'   => 'credit',
                'level'            => 2,
                'sort_order'       => $index + 1,
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
            'is_header'        => 1,
            'is_postable'      => 0,
            'level'            => 1,
            'sort_order'       => 4,
        ]);

        /*
        |--------------------------------------------------------------------------
        | NON FUND REVENUE
        |--------------------------------------------------------------------------
        */

        $revenues = [
            ['4110', 'Pendapatan Administrasi'],
            ['4120', 'Pendapatan Denda'],
            ['4130', 'Donasi Bebas'],
            ['4140', 'Pendapatan Lain-lain'],
        ];

        foreach ($revenues as $index => $rev) {

            $create([
                'parent_id'        => $revenue->id,
                'code'             => $rev[0],
                'name'             => $rev[1],
                'type'             => 'revenue',
                'normal_balance'   => 'credit',
                'level'            => 2,
                'sort_order'       => $index + 1,
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
            'is_header'        => 1,
            'is_postable'      => 0,
            'level'            => 1,
            'sort_order'       => 5,
        ]);

        $operational = $create([
            'parent_id'        => $expense->id,
            'code'             => '5100',
            'name'             => 'OPERATIONAL EXPENSE',
            'type'             => 'expense',
            'normal_balance'   => 'debit',
            'is_header'        => 1,
            'is_postable'      => 0,
            'level'            => 2,
            'sort_order'       => 1,
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
        ];

        foreach ($expenses as $index => $exp) {

            $create([
                'parent_id'        => $operational->id,
                'code'             => $exp[0],
                'name'             => $exp[1],
                'type'             => 'expense',
                'normal_balance'   => 'debit',
                'level'            => 3,
                'sort_order'       => $index + 1,
            ]);
        }
    }
}
