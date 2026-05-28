<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Exception;

class FundAccountLinksSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        /*
        |--------------------------------------------------
        | MASTER DATA
        |--------------------------------------------------
        */

        $roles = DB::table('account_roles')
            ->pluck('id', 'code');

        $coas = DB::table('chart_of_accounts')
            ->pluck('id', 'code');

        $funds = DB::table('fund_types')
            ->pluck('id', 'code');

        /*
        |--------------------------------------------------
        | ORGANIZATION
        |--------------------------------------------------
        */

        $rw = DB::table('organizations')
            ->where('type', 'rw')
            ->first();

        $rts = DB::table('organizations')
            ->where('type', 'rt')
            ->get();

        if (!$rw) {
            throw new Exception('RW organization tidak ditemukan.');
        }

        if ($rts->isEmpty()) {
            throw new Exception('RT organization tidak ditemukan.');
        }

        /*
        |--------------------------------------------------
        | REQUIRED VALIDATION
        |--------------------------------------------------
        */

        $requiredFunds = [
            'KAS-RW',
            'KAS-RT',
            'SMP',
            'INF',
            'RKM',
        ];

        foreach ($requiredFunds as $code) {
            if (!isset($funds[$code])) {
                throw new Exception("Fund type {$code} tidak ditemukan.");
            }
        }

        $requiredRoles = [
            'CASH',
            'BANK',
            'RECEIVABLE',
            'FUND_BALANCE',
            'REVENUE',
            'EXPENSE',
            'HOLDER',
            'PAYABLE',
        ];

        foreach ($requiredRoles as $role) {
            if (!isset($roles[$role])) {
                throw new Exception("Role {$role} tidak ditemukan.");
            }
        }

        /*
        |--------------------------------------------------
        | TEMPLATE MAP
        |--------------------------------------------------
        */

        $templates = [

            /*
            |--------------------------------------------------
            | KAS RT
            |--------------------------------------------------
            */

            'KAS-RT' => [

                // Kas dana RT
                [
                    'coa'     => '1121',
                    'role'    => 'CASH',
                    'default' => 1,
                ],

                // Bank penampung
                [
                    'coa'     => '1112',
                    'role'    => 'BANK',
                    'default' => 1,
                ],

                // Piutang IPL RT
                [
                    'coa'     => '1210',
                    'role'    => 'RECEIVABLE',
                    'default' => 1,
                ],

                // Dana RT (liability)
                [
                    'coa'     => '2210',
                    'role'    => 'FUND_BALANCE',
                    'default' => 1,
                ],

                // Pendapatan IPL
                [
                    'coa'     => '4110',
                    'role'    => 'REVENUE',
                    'default' => 1,
                ],

                // Beban kegiatan RT
                [
                    'coa'     => '5200',
                    'role'    => 'EXPENSE',
                    'default' => 1,
                ],

                // Titipan petugas RT
                [
                    'coa'     => '1115',
                    'role'    => 'HOLDER',
                    'default' => 1,
                ],

                // Hutang titipan petugas
                [
                    'coa'     => '2140',
                    'role'    => 'PAYABLE',
                    'default' => 1,
                ],
            ],

            /*
            |--------------------------------------------------
            | KAS RW
            |--------------------------------------------------
            */

            'KAS-RW' => [

                [
                    'coa'     => '1122',
                    'role'    => 'CASH',
                    'default' => 1,
                ],

                [
                    'coa'     => '1112',
                    'role'    => 'BANK',
                    'default' => 1,
                ],

                [
                    'coa'     => '1210',
                    'role'    => 'RECEIVABLE',
                    'default' => 1,
                ],

                [
                    'coa'     => '2220',
                    'role'    => 'FUND_BALANCE',
                    'default' => 1,
                ],

                [
                    'coa'     => '4110',
                    'role'    => 'REVENUE',
                    'default' => 1,
                ],

                [
                    'coa'     => '5140',
                    'role'    => 'EXPENSE',
                    'default' => 1,
                ],

                [
                    'coa'     => '2150',
                    'role'    => 'PAYABLE',
                    'default' => 1,
                ],
            ],

            /*
            |--------------------------------------------------
            | DANA SAMPAH
            |--------------------------------------------------
            */

            'SMP' => [

                [
                    'coa'     => '1123',
                    'role'    => 'CASH',
                    'default' => 1,
                ],

                [
                    'coa'     => '2230',
                    'role'    => 'FUND_BALANCE',
                    'default' => 1,
                ],

                [
                    'coa' => '4170',
                    'role' => 'REVENUE',
                    'default' => 1,
                ],

                [
                    'coa'     => '5150',
                    'role'    => 'EXPENSE',
                    'default' => 1,
                ],
            ],

            /*
            |--------------------------------------------------
            | DANA INFRASTRUKTUR
            |--------------------------------------------------
            */

            'INF' => [

                [
                    'coa'     => '1125',
                    'role'    => 'CASH',
                    'default' => 1,
                ],

                [
                    'coa'     => '2250',
                    'role'    => 'FUND_BALANCE',
                    'default' => 1,
                ],

                [
                    'coa'     => '4160',
                    'role'    => 'REVENUE',
                    'default' => 1,
                ],

                [
                    'coa'     => '5170',
                    'role'    => 'EXPENSE',
                    'default' => 1,
                ],
            ],

            /*
            |--------------------------------------------------
            | DANA RUKEM
            |--------------------------------------------------
            */

            'RKM' => [

                [
                    'coa'     => '1126',
                    'role'    => 'CASH',
                    'default' => 1,
                ],

                [
                    'coa'     => '2260',
                    'role'    => 'FUND_BALANCE',
                    'default' => 1,
                ],

                [
                    'coa'     => '4180',
                    'role'    => 'REVENUE',
                    'default' => 1,
                ],

                [
                    'coa'     => '5190',
                    'role'    => 'EXPENSE',
                    'default' => 1,
                ],
            ],
        ];

        /*
        |--------------------------------------------------
        | BUILD INSERT
        |--------------------------------------------------
        */

        $rows = [];

        $insertFund = function (
            string $fundCode,
            int $organizationId,
            array $template
        ) use (
            &$rows,
            $funds,
            $coas,
            $roles,
            $now
        ) {

            foreach ($template as $map) {

                if (!isset($coas[$map['coa']])) {
                    throw new Exception(
                        "COA {$map['coa']} tidak ditemukan."
                    );
                }

                if (!isset($roles[$map['role']])) {
                    throw new Exception(
                        "Role {$map['role']} tidak ditemukan."
                    );
                }

                $rows[] = [
                    'fund_type_id'    => $funds[$fundCode],
                    'coa_id'          => $coas[$map['coa']],
                    'account_role_id' => $roles[$map['role']],
                    'organization_id' => $organizationId,

                    'priority'        => 1,
                    'is_default'      => $map['default'],
                    'is_active'       => 1,

                    'created_at'      => $now,
                    'updated_at'      => $now,
                ];
            }
        };

        /*
        |--------------------------------------------------
        | RW INSERT
        |--------------------------------------------------
        */

        foreach (['KAS-RW', 'SMP', 'INF', 'RKM'] as $fundCode) {
            $insertFund(
                $fundCode,
                $rw->id,
                $templates[$fundCode]
            );
        }

        /*
        |--------------------------------------------------
        | RT INSERT
        |--------------------------------------------------
        */

        foreach ($rts as $rt) {

            $insertFund(
                'KAS-RT',
                $rt->id,
                $templates['KAS-RT']
            );
        }

        /*
        |--------------------------------------------------
        | RESET TABLE
        |--------------------------------------------------
        */

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('fund_account_links')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        /*
        |--------------------------------------------------
        | INSERT
        |--------------------------------------------------
        */

        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('fund_account_links')
                ->insert($chunk);
        }

        $this->command->info(
            'Fund Account Links seeded successfully.'
        );
    }
}
