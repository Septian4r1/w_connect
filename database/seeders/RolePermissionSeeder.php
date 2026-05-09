<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        DB::beginTransaction();

        try {

            /**
             * ======================================================
             * PERMISSIONS (MENU + CRUD)
             * ======================================================
             */
            $permissions = [

                // DASHBOARD
                'view_dashboard',

                // USER MANAGEMENT
                'manage_users',
                'manage_roles',
                'manage_struktur',

                // WARGA
                'manage_warga',
                'view_warga',
                'create_warga',
                'edit_warga',
                'delete_warga',

                // KEUANGAN
                'manage_keuangan',
                'view_keuangan',
                'create_keuangan',
                'edit_keuangan',
                'delete_keuangan',

                // SURAT
                'manage_surat_pengantar',
                'view_surat_pengantar',
                'create_surat_pengantar',
                'edit_surat_pengantar',
                'delete_surat_pengantar',
            ];

            foreach ($permissions as $perm) {
                Permission::firstOrCreate([
                    'name' => $perm,
                    'guard_name' => 'web'
                ]);
            }

            /**
             * ======================================================
             * ROLES
             * ======================================================
             */
            $roles = [
                // RW
                'ketua_rw',
                'sekretaris_rw',
                'bendahara_rw',

                // RT
                'ketua_rt',
                'sekretaris_rt',
                'bendahara_rt',

                // Seksi
                'seksi_keamanan',
                'seksi_kebersihan',
                'seksi_sosial',
                'seksi_kesehatan',
                'seksi_pemuda',
                'seksi_agama',
            ];

            foreach ($roles as $role) {
                Role::firstOrCreate([
                    'name' => $role,
                    'guard_name' => 'web'
                ]);
            }

            /**
             * ======================================================
             * ASSIGN PERMISSION KE ROLE
             * ======================================================
             */

            // 🔥 KETUA RW (FULL ACCESS RW)
            Role::findByName('ketua_rw')->syncPermissions($permissions);

            // 🧾 SEKRETARIS RW
            Role::findByName('sekretaris_rw')->syncPermissions([
                'view_dashboard',

                'manage_warga',
                'view_warga',
                'create_warga',
                'edit_warga',

                'manage_surat_pengantar',
                'view_surat_pengantar',
                'create_surat_pengantar',
                'edit_surat_pengantar',
            ]);

            // 💰 BENDAHARA RW
            Role::findByName('bendahara_rw')->syncPermissions([
                'view_dashboard',

                'view_keuangan',
                'manage_keuangan',
                'create_keuangan',
                'edit_keuangan',
                'delete_keuangan',
            ]);

            // 🏠 KETUA RT
            Role::findByName('ketua_rt')->syncPermissions([
                'view_dashboard',

                'manage_warga',
                'view_warga',
                'edit_warga',

                'manage_surat_pengantar',
                'view_surat_pengantar',
            ]);

            // 🧾 SEKRETARIS RT
            Role::findByName('sekretaris_rt')->syncPermissions([
                'view_dashboard',

                'manage_warga',
                'view_warga',
                'create_warga',
                'edit_warga',

                'manage_surat_pengantar',
                'create_surat_pengantar',
            ]);

            // 💰 BENDAHARA RT
            Role::findByName('bendahara_rt')->syncPermissions([
                'view_dashboard',

                'view_keuangan',
                'manage_keuangan',
                'create_keuangan',
            ]);

            // 🧩 SEKSI
            $seksiPermissions = [
                'view_dashboard',
                'view_warga',
                'manage_surat_pengantar',
            ];

            $seksiRoles = [
                'seksi_keamanan',
                'seksi_kebersihan',
                'seksi_sosial',
                'seksi_kesehatan',
                'seksi_pemuda',
                'seksi_agama',
            ];

            foreach ($seksiRoles as $role) {
                Role::findByName($role)->syncPermissions($seksiPermissions);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
