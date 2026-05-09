<?php

namespace App\Http\Controllers\Management\Menus;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        \Illuminate\Support\Facades\Cache::flush(); // 🔥 penting

        $menus = Menu::with([
            'permissions',
            'childrenRecursive.permissions'
        ])
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get();

        $permissions = Permission::orderBy('name')->get();

        return view('backend.management.menu.index', compact('menus', 'permissions'));
    }

    public function store(Request $request)
    {
        try {

            // =========================
            // VALIDASI
            // =========================
            $request->validate([
                'name' => 'required|string|max:255',
                'route' => 'nullable|string|max:255',
                'icon' => 'nullable|string|max:255',
                'parent_id' => 'nullable|exists:menus,id',
                'order' => 'nullable|integer',
                'is_active' => 'required|boolean',
                'permissions' => 'nullable|array'
            ]);

            // =========================
            // SIMPAN MENU
            // =========================
            $menu = Menu::create([
                'name' => $request->name,
                'route' => $request->route,
                'icon' => $request->icon,
                'parent_id' => $request->parent_id,
                'order' => $request->order ?? 0,
                'is_active' => $request->is_active,
            ]);

            // =========================
            // HANDLE PERMISSIONS (FIXED)
            // =========================
            $menu->permissions()->sync($request->permissions ?? []);

            return response()->json([
                'success' => true,
                'message' => 'Menu berhasil disimpan'
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(Request $request, String $id)
    {
        try {

            $id = decrypt($id);

            $menu = Menu::findOrFail($id);

            $menu->update([
                'name' => $request->name,
                'route' => $request->route,
                'icon' => $request->icon,
                'order' => $request->order,
                'is_active' => $request->is_active,
            ]);

            if ($request->has('permissions')) {
                $menu->permissions()->sync($request->permissions);
            }

            // 🔥 SPATIE CACHE CLEAR
            $userId = Auth::id() ?? 'guest';
            Cache::forget('menus_user_' . $userId);
            app()[PermissionRegistrar::class]->forgetCachedPermissions();

            // 🔥 INI YANG KAMU LUPA (IMPORTANT)
            Cache::flush(); // atau lebih aman pakai key spesifik (lihat bawah)

            return response()->json([
                'success' => true,
                'message' => 'Menu berhasil diupdate'
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updatePermission(Request $request, string $id)
    {
        try {
            $id = Crypt::decrypt($id);

            $request->validate([
                'name' => 'required|string|unique:permissions,name,' . $id
            ]);

            $permission = Permission::findOrFail($id);
            $permission->name = $request->name;
            $permission->save();

            return response()->json([
                'success' => true,
                'message' => 'Permission berhasil diupdate',
                'data' => [
                    'id' => encrypt($permission->id), // kirim lagi encrypted
                    'name' => $permission->name
                ]
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Gagal update permission'
            ], 500);
        }
    }


    public function StorePermission(Request $request)
    {
        try {

            $request->validate([
                'name' => 'required|string|max:255|unique:permissions,name,NULL,id,guard_name,web'
            ]);

            // 🔥 buat permission
            $permission = Permission::create([
                'name' => strtolower($request->name),
                'guard_name' => 'web'
            ]);

            // 🔥 ambil role super_admin
            $role = Role::where('name', 'super_admin')->first();

            if ($role) {
                // kasih permission ke super_admin
                $role->givePermissionTo($permission);
            }

            // 🔥 WAJIB: reset cache spatie
            app()[PermissionRegistrar::class]->forgetCachedPermissions();

            return response()->json([
                'success' => true,
                'message' => 'Permission berhasil ditambahkan',
                'data' => [
                    'id' => encrypt($permission->id),
                    'name' => $permission->name
                ]
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(), // 🔥 tampilkan error asli
            ], 500);
        }
    }


    public function destroyPermission(string $id)
    {
        try {
            $id = Crypt::decrypt($id);

            $permission = Permission::findOrFail($id);

            // hapus relasi role
            $permission->roles()->detach();

            // hapus relasi menu pivot
            DB::table('menu_permissions')
                ->where('permission_id', $id)
                ->delete();

            // hapus permission
            $permission->delete();

            // clear cache spatie
            app()[PermissionRegistrar::class]->forgetCachedPermissions();

            return response()->json([
                'success' => true,
                'message' => 'Permission berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
