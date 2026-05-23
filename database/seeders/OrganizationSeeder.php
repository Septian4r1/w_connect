<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Organization;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |------------------------------------------------------
        | RW LEVEL
        |------------------------------------------------------
        */

        $rw06 = Organization::create([
            'type' => 'rw',
            'code' => 'RW06',
            'name' => 'RW 06',
            'parent_id' => null,
            'is_active' => 1,
        ]);

        /*
        |------------------------------------------------------
        | RT LEVEL (child RW 06)
        |------------------------------------------------------
        */

        $rt001 = Organization::create([
            'type' => 'rt',
            'code' => 'RT001',
            'name' => 'RT 001',
            'parent_id' => $rw06->id,
            'is_active' => 1,
        ]);

        $rt002 = Organization::create([
            'type' => 'rt',
            'code' => 'RT002',
            'name' => 'RT 002',
            'parent_id' => $rw06->id,
            'is_active' => 1,
        ]);

        $rt003 = Organization::create([
            'type' => 'rt',
            'code' => 'RT003',
            'name' => 'RT 003',
            'parent_id' => $rw06->id,
            'is_active' => 1,
        ]);

        /*
        |------------------------------------------------------
        | VENDOR / SYSTEM
        |------------------------------------------------------
        */

        Organization::create([
            'type' => 'vendor',
            'code' => 'DLHK',
            'name' => 'Dinas Lingkungan Hidup & Kebersihan',
            'parent_id' => $rw06->id,
            'is_active' => 1,
        ]);

        /*
        |------------------------------------------------------
        | LEMBAGA
        |------------------------------------------------------
        */

        Organization::create([
            'type' => 'lembaga',
            'code' => 'RKM',
            'name' => 'Lembaga Rukem',
            'parent_id' => $rw06->id,
            'is_active' => 1,
        ]);
    }
}
