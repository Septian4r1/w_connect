<?php

namespace App\Services;

use App\Models\Menu;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class MenuPermissionSyncService
{
    public function sync(): void
    {
        $menus = Menu::all();

        foreach ($menus as $menu) {

            $permissions = $this->generatePermissions($menu);

            foreach ($permissions as $permName) {

                $permission = Permission::firstOrCreate([
                    'name' => $permName,
                    'guard_name' => 'web'
                ]);

                DB::table('menu_permissions')->updateOrInsert([
                    'menu_id' => $menu->id,
                    'permission_id' => $permission->id,
                ], [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    private function generatePermissions(Menu $menu): array
    {
        if (!$menu->route) return [];

        $base = explode('.', $menu->route)[0] ?? null;

        if (!$base) return [];

        return [
            "{$base}.view",
            "{$base}.create",
            "{$base}.update",
            "{$base}.delete",
        ];
    }
}
