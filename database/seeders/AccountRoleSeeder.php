<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AccountRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('account_roles')->insert([

            /*
            |--------------------------------------------------------------------------
            | CASH & BANK
            |--------------------------------------------------------------------------
            */

            [
                'code' => 'CASH',
                'name' => 'Cash Account',
                'description' => 'Akun kas fisik yang digunakan untuk operasional dan penyimpanan uang tunai.',
                'coa_type' => 'asset',
                'normal_balance' => 'debit',
                'is_system' => true,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            [
                'code' => 'BANK',
                'name' => 'Bank Account',
                'description' => 'Akun rekening bank yang digunakan untuk transaksi dan penyimpanan dana.',
                'coa_type' => 'asset',
                'normal_balance' => 'debit',
                'is_system' => true,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            /*
            |--------------------------------------------------------------------------
            | HOLDER ACCOUNT
            |--------------------------------------------------------------------------
            */

            [
                'code' => 'HOLDER',
                'name' => 'Holder Account',
                'description' => 'Akun penampung sementara dana hasil penagihan petugas lapangan sebelum disetor ke kas RW atau rekening bank.',
                'coa_type' => 'asset',
                'normal_balance' => 'debit',
                'is_system' => true,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            /*
            |--------------------------------------------------------------------------
            | FUND ACCOUNTING
            |--------------------------------------------------------------------------
            */

            [
                'code' => 'FUND_BALANCE',
                'name' => 'Fund Balance',
                'description' => 'Akun saldo dana yang digunakan untuk mencatat dana titipan atau dana khusus.',
                'coa_type' => 'liability',
                'normal_balance' => 'credit',
                'is_system' => true,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            /*
            |--------------------------------------------------------------------------
            | REVENUE & EXPENSE
            |--------------------------------------------------------------------------
            */

            [
                'code' => 'REVENUE',
                'name' => 'Revenue Account',
                'description' => 'Akun pendapatan yang digunakan untuk mencatat pemasukan suatu dana.',
                'coa_type' => 'revenue',
                'normal_balance' => 'credit',
                'is_system' => true,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            [
                'code' => 'EXPENSE',
                'name' => 'Expense Account',
                'description' => 'Akun beban atau pengeluaran yang digunakan untuk operasional dana.',
                'coa_type' => 'expense',
                'normal_balance' => 'debit',
                'is_system' => true,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            /*
            |--------------------------------------------------------------------------
            | RECEIVABLE & PAYABLE
            |--------------------------------------------------------------------------
            */

            [
                'code' => 'RECEIVABLE',
                'name' => 'Receivable Account',
                'description' => 'Akun piutang yang digunakan untuk mencatat tagihan atau kewajiban yang belum dibayar.',
                'coa_type' => 'asset',
                'normal_balance' => 'debit',
                'is_system' => true,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            [
                'code' => 'PAYABLE',
                'name' => 'Payable Account',
                'description' => 'Akun hutang yang digunakan untuk mencatat kewajiban pembayaran kepada pihak lain.',
                'coa_type' => 'liability',
                'normal_balance' => 'credit',
                'is_system' => true,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            /*
            |--------------------------------------------------------------------------
            | EQUITY
            |--------------------------------------------------------------------------
            */

            [
                'code' => 'EQUITY',
                'name' => 'Equity Account',
                'description' => 'Akun modal atau ekuitas yang mencerminkan kepemilikan dan saldo modal.',
                'coa_type' => 'equity',
                'normal_balance' => 'credit',
                'is_system' => true,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],

        ]);
    }
}
