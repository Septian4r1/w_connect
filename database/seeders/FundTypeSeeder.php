<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Accounting\FundType;

class FundTypeSeeder extends Seeder
{
    /**
     * =========================================================
     * RUN SEEDER
     * =========================================================
     *
     * Production Ready:
     * - updateOrCreate
     * - prevent duplicate
     * - safe rerun
     * - transaction
     * - timestamp sync
     *
     * =========================================================
     */
    public function run(): void
    {
        DB::transaction(function () {

            $now = now();

            $fundTypes = [

                [
                    'code'        => 'SMP',
                    'name'        => 'Dana Sampah',
                    'description' => 'Dana pengelolaan kebersihan dan sampah lingkungan',
                    'is_active'   => true,
                ],

                [
                    'code'        => 'INF',
                    'name'        => 'Dana Infrastruktur',
                    'description' => 'Dana pembangunan dan perbaikan fasilitas lingkungan',
                    'is_active'   => true,
                ],

                [
                    'code'        => 'RKM',
                    'name'        => 'Dana Rukem',
                    'description' => 'Dana Kedukaan Untuk Warga',
                    'is_active'   => true,
                ],

                [
                    'code'        => 'KAS-RT',
                    'name'        => 'Dana Kas RT',
                    'description' => 'Dana Kas RT Untuk Operational dll',
                    'is_active'   => true,
                ],

                [
                    'code'        => 'KAS-RW',
                    'name'        => 'Dana Kas RW',
                    'description' => 'Dana Kas RW Untuk Operational Dll',
                    'is_active'   => true,
                ],

            ];

            foreach ($fundTypes as $fundType) {

                FundType::updateOrCreate(

                    [
                        'code' => $fundType['code'],
                    ],

                    [
                        'name'        => trim($fundType['name']),
                        'description' => trim($fundType['description']),
                        'is_active'   => (bool) $fundType['is_active'],
                        'updated_at'  => $now,
                    ]

                );
            }
        });
    }
}
