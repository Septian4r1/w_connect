<?php

namespace App\Http\Controllers\Management\Menus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\Models\Menu;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionsController extends Controller
{
    /**
     * ===============================
     * HALAMAN UTAMA
     * ===============================
     */
    public function index()
    {
        // ❌ JANGAN flush semua cache
        // Cache::flush();

        // 🔥 hanya clear permission cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $roles = Role::query()
            // ->where('name', '!=', 'super_admin')
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('backend.management.menu.role_permissions.index', [
            'roles' => $roles,
            'selectedRole' => null
        ]);
    }

    /**
     * ===============================
     * GET MENU TREE + PERMISSIONS
     * ===============================
     */
    public function getTree(string $roleId)
    {
        $role = Role::query()->findOrFail($roleId);

        // 🔥 ambil ID saja (lebih cepat & ringan)
        $rolePermissions = $role->permissions()
            ->pluck('permissions.id')
            ->toArray();

        $menus = Menu::query()
            ->with([
                'permissions:id,name',
                'childrenRecursive.permissions:id,name'
            ])
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get();

        return response()->json([
            'status' => true,
            'menus' => $menus,
            'rolePermissions' => $rolePermissions
        ]);
    }

    /**
     * ===============================
     * SYNC PERMISSIONS (CORE LOGIC)
     * ===============================
     */
    public function sync(Request $request)
    {
        // 🔥 VALIDATION (WAJIB DI PRODUCTION)
        $validated = $request->validate([
            'role_id' => ['required', 'exists:roles,id'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['integer', 'exists:permissions,id'],
        ]);

        $roleId = $validated['role_id'];
        $permissionIds = $validated['permissions'] ?? [];

        $role = Role::query()->findOrFail($roleId);

        try {

            // hapus duplicate ID
            $permissionIds = array_unique($permissionIds);

            DB::transaction(function () use ($role, $permissionIds) {

                // validasi permission yg benar-benar ada
                $validPermissionIds = Permission::whereIn('id', $permissionIds)
                    ->pluck('id')
                    ->toArray();

                // sync otomatis handle insert/delete
                $role->permissions()->sync($validPermissionIds);
            });

            app()[PermissionRegistrar::class]->forgetCachedPermissions();

            Log::info('Role permissions synced', [
                'role_id' => $role->id,
                'permissions' => $permissionIds,
                'updated_by' => Auth::id()
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Permissions updated successfully'
            ]);
        } catch (\Throwable $e) {

            Log::error('Failed to sync permissions', [
                'error' => $e->getMessage(),
                'role_id' => $roleId
            ]);

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
