<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Accounting\FundType;
use App\Models\Accounting\ChartOfAccount;
use App\Models\Accounting\FundAccountMapping;

class FundAccountMappingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | LOAD FUND TYPES
        |--------------------------------------------------------------------------
        */
        $fundTypes = FundType::query()
            ->where('is_active', true)
            ->get()
            ->keyBy('code');

        /*
        |--------------------------------------------------------------------------
        | LOAD COA
        |--------------------------------------------------------------------------
        */
        $accounts = ChartOfAccount::query()
            ->where('is_active', true)
            ->get()
            ->keyBy('code');

        /*
        |--------------------------------------------------------------------------
        | SAFETY CHECK
        |--------------------------------------------------------------------------
        */
        if ($fundTypes->isEmpty()) {

            $this->command->error(
                'Fund types table is empty.'
            );

            return;
        }

        if ($accounts->isEmpty()) {

            $this->command->error(
                'Chart of accounts table is empty.'
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | FUND ACCOUNT MAPPINGS
        |--------------------------------------------------------------------------
        |
        | Mapping disesuaikan dengan COA yang BENAR-BENAR ADA
        | di tabel chart_of_accounts.
        |
        */

        $mappings = [

            /*
            |--------------------------------------------------------------------------
            | DANA SAMPAH
            |--------------------------------------------------------------------------
            */
            'SMP' => [

                // CASH
                'cash_account' => '1123',

                // REVENUE
                'revenue_account' => '4140',

                // EXPENSE
                'expense_account' => '5150',

                // LIABILITY
                'payable_account' => '2230',

                // RECEIVABLE
                'receivable_account' => '1210',

                'notes' => 'Default mapping Dana Sampah',
            ],

            /*
            |--------------------------------------------------------------------------
            | DANA INFRASTRUKTUR
            |--------------------------------------------------------------------------
            */
            'INF' => [

                'cash_account' => '1125',

                'revenue_account' => '4140',

                'expense_account' => '5170',

                'payable_account' => '2250',

                'receivable_account' => '1210',

                'notes' => 'Default mapping Dana Infrastruktur',
            ],

            /*
            |--------------------------------------------------------------------------
            | DANA RUKEM
            |--------------------------------------------------------------------------
            */
            'RKM' => [

                'cash_account' => '1126',

                'revenue_account' => '4140',

                'expense_account' => '5190',

                'payable_account' => '2260',

                'receivable_account' => '1210',

                'notes' => 'Default mapping Dana Rukem',
            ],

            /*
            |--------------------------------------------------------------------------
            | DANA KAS RT
            |--------------------------------------------------------------------------
            */
            'KAS-RT' => [

                'cash_account' => '1121',

                'revenue_account' => '4110',

                'expense_account' => '5200',

                'payable_account' => '2210',

                'receivable_account' => '1210',

                'notes' => 'Default mapping Dana Kas RT',
            ],

            /*
            |--------------------------------------------------------------------------
            | DANA KAS RW
            |--------------------------------------------------------------------------
            */
            'KAS-RW' => [

                'cash_account' => '1122',

                'revenue_account' => '4110',

                'expense_account' => '5140',

                'payable_account' => '2220',

                'receivable_account' => '1210',

                'notes' => 'Default mapping Dana Kas RW',
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | PROCESS MAPPINGS
        |--------------------------------------------------------------------------
        */
        foreach ($mappings as $fundCode => $mapping) {

            /*
            |--------------------------------------------------------------------------
            | FIND FUND TYPE
            |--------------------------------------------------------------------------
            */
            $fundType = $fundTypes->get($fundCode);

            if (!$fundType) {

                $this->command->warn(
                    "Fund type not found: {$fundCode}"
                );

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | FIND ACCOUNTS
            |--------------------------------------------------------------------------
            */
            $cashAccount = $accounts->get(
                $mapping['cash_account']
            );

            $revenueAccount = $accounts->get(
                $mapping['revenue_account']
            );

            $expenseAccount = $accounts->get(
                $mapping['expense_account']
            );

            $payableAccount = $accounts->get(
                $mapping['payable_account']
            );

            $receivableAccount = $accounts->get(
                $mapping['receivable_account']
            );

            /*
            |--------------------------------------------------------------------------
            | VALIDATION
            |--------------------------------------------------------------------------
            */
            if (
                !$cashAccount ||
                !$revenueAccount ||
                !$expenseAccount
            ) {

                $this->command->warn(
                    "Incomplete COA mapping for fund: {$fundCode}"
                );

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | UPSERT MAPPING
            |--------------------------------------------------------------------------
            */
            FundAccountMapping::updateOrCreate(

                [
                    'fund_type_id' => $fundType->id,
                    'is_default'  => true,
                ],

                [
                    /*
                    |--------------------------------------------------------------------------
                    | ACCOUNT IDS
                    |--------------------------------------------------------------------------
                    */
                    'cash_account_id' => $cashAccount->id,

                    'revenue_account_id' => $revenueAccount->id,

                    'expense_account_id' => $expenseAccount->id,

                    'payable_account_id' => $payableAccount?->id,

                    'receivable_account_id' => $receivableAccount?->id,

                    /*
                    |--------------------------------------------------------------------------
                    | STATUS
                    |--------------------------------------------------------------------------
                    */
                    'is_active' => true,

                    /*
                    |--------------------------------------------------------------------------
                    | NOTES
                    |--------------------------------------------------------------------------
                    */
                    'notes' => $mapping['notes'],

                    /*
                    |--------------------------------------------------------------------------
                    | AUDIT
                    |--------------------------------------------------------------------------
                    */
                    'created_by' => null,
                    'updated_by' => null,
                ]
            );

            $this->command->info(
                "Mapping created successfully: {$fundCode}"
            );
        }

        /*
        |--------------------------------------------------------------------------
        | DONE
        |--------------------------------------------------------------------------
        */
        $this->command->info(
            'FundAccountMappingSeeder completed successfully.'
        );
    }
}
