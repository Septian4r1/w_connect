<?php

namespace App\Http\Controllers\Management\Roles;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rw;
use App\Models\Rt;
use App\Models\Organization;
use App\Models\User;
use App\Models\Warga;
use App\Models\PengurusWilayah;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;

class ManagementRolseController extends Controller
{
    // ===========================
    // INDEX (ANTI N+1 + OPTIMIZED)
    // ===========================


    public function index()
    {
        /*
    |--------------------------------------------------------------------------
    | ROLES
    |--------------------------------------------------------------------------
    */
        $roles = Role::query()
            ->select(['id', 'name', 'guard_name'])
            ->where('guard_name', 'web')
            ->where('name', '!=', 'super_admin')
            ->withCount([
                'permissions as permissions_count',
                'users as users_count'
            ])
            ->latest('id')
            ->get();

    /*
    |--------------------------------------------------------------------------
    | USER LOGIN
    |--------------------------------------------------------------------------
    */
        /** @var User $user */
        $user = Auth::user();

        $myPengurus = null;

        if (!$user->hasRole('super_admin')) {

            $myPengurus = DB::table('pengurus_wilayah')
                ->where('user_id', $user->id)
                ->where('status', 'aktif')
                ->whereNull('end_date') // 🔥 penting untuk histori
                ->first();
        }

        /*
    |--------------------------------------------------------------------------
    | PENGURUS WILAYAH (ERP VERSION - HISTORI SAFE)
    |--------------------------------------------------------------------------
    */
        $pengurus = PengurusWilayah::query()
            ->select([
                'id',
                'user_id',
                'role_id',
                'organization_id',
                'rw_id',
                'rt_id',
                'status',
                'start_date',   // ✅ TAMBAH INI
                'end_date',     // ✅ TAMBAH INI
                'created_at'
            ])

            // 🔥 ACTIVE ONLY (HISTORI SAFE)
            // ->where('status', 'aktif')
            // ->whereNull('end_date')

            // exclude super admin
            ->whereHas('role', function ($q) {
                $q->where('name', '!=', 'super_admin');
            })

            // FILTER RT LOGIN (legacy support)
            ->when($myPengurus && $myPengurus->rt_id !== null, function ($q) use ($myPengurus) {
                $q->where('rt_id', $myPengurus->rt_id);
            })

            ->with([
                'user:id,warga_id,name,email',

                'user.warga:id,keluarga_id,nama,no_hp,foto',
                'user.warga.keluarga:id,rumah_id',
                'user.warga.keluarga.rumah:id,nomor_rumah',

                'role:id,name',

                'organization:id,type,code,name,parent_id,is_active',

                'rt:id,nama_rt',
                'rw:id,nama_rw',
            ])

            ->latest('start_date') // 🔥 lebih logis daripada created_at
            ->get();

        /*
    |--------------------------------------------------------------------------
    | ORGANIZATIONS
    |--------------------------------------------------------------------------
    */
        $organizations = Organization::query()
            ->select([
                'id',
                'type',
                'code',
                'name',
                'parent_id',
                'is_active'
            ])
            ->where('is_active', 1)
            ->orderBy('type')
            ->orderBy('id')
            ->get();

        /*
    |--------------------------------------------------------------------------
    | WARGA
    |--------------------------------------------------------------------------
    */
        $wargas = Warga::query()
            ->select([
                'id',
                'nama',
                'nik',
                'no_hp',
                'keluarga_id',
                'foto'
            ])
            ->with([
                'keluarga:id,rumah_id',
                'keluarga.rumah:id,nomor_rumah'
            ])
            ->where('status', 'aktif')
            ->latest()
            ->get();

        /*
    |--------------------------------------------------------------------------
    | RW & RT
    |--------------------------------------------------------------------------
    */
        $rws = Rw::query()
            ->select('id', 'nama_rw')
            ->where('status', 'aktif')
            ->get();

        $rts = Rt::query()
            ->select('id', 'rw_id', 'nama_rt')
            ->where('status', 'aktif')
            ->get();

        /*
    |--------------------------------------------------------------------------
    | RETURN VIEW
    |--------------------------------------------------------------------------
    */
        return view('backend.management.roles.index', compact(
            'roles',
            'pengurus',
            'wargas',
            'rws',
            'rts',
            'organizations'
        ));
    }

    // ===========================
    // STORE ROLE (SAFE + CLEAN INPUT)
    // ===========================
    public function store(Request $request)
    {
        // dd($request);
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                'unique:roles,name'
            ]
        ]);

        try {

            DB::beginTransaction();

            Role::create([
                'name' => $this->sanitizeRoleName($validated['name']),
                'guard_name' => 'web'
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Role berhasil ditambahkan',
            ], 201);
        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menambahkan role',
            ], 500);
        }
    }


    // ===========================
    // UPDATE ROLE (SAFE + ANTI DUPLIKAT + TRANSACTION)
    // ===========================
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100'
            ]
        ]);

        try {

            DB::beginTransaction();

            $role = Role::query()
                ->where('id', $id)
                ->where('guard_name', 'web')
                ->firstOrFail();

            // cek duplicate (lebih aman & indexed friendly)
            $duplicate = Role::query()
                ->where('name', $validated['name'])
                ->where('id', '!=', $id)
                ->exists();

            if ($duplicate) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Nama role sudah digunakan'
                ], 422);
            }

            $role->update([
                'name' => $this->sanitizeRoleName($validated['name'])
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Role berhasil diupdate',
                'data' => [
                    'id' => $role->id,
                    'name' => $role->name
                ]
            ]);
        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal update role'
            ], 500);
        }
    }


    // ===========================
    // SANITIZER (ANTI SCRIPT INJECTION / XSS BASIC)
    // ===========================
    private function sanitizeRoleName(string $name): string
    {
        // trim + remove script tag + encode special chars
        $name = trim($name);

        // remove HTML tags
        $name = strip_tags($name);

        // optional: normalize spacing
        $name = preg_replace('/\s+/', ' ', $name);

        return $name;
    }


    public function AksesStore(Request $request)
    {
        $validated = $request->validate([
            'user_id'          => ['required', 'exists:wargas,id'],
            'email'            => ['required', 'email'],
            'role_id'          => ['required', 'exists:roles,id'],
            'organization_id'  => ['nullable', 'exists:organizations,id'],
            'rw_id'            => ['nullable', 'exists:rws,id'],
            'rt_id'            => ['nullable', 'exists:rts,id'],
            'status'           => ['required', 'in:aktif,nonaktif'],

            // 🔥 TAMBAHAN JABATAN
            'start_date'       => ['nullable', 'date'],
            'end_date'         => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        try {
            DB::beginTransaction();

            /*
        |-----------------------------------------
        | CHECK WARGA
        |-----------------------------------------
        */
            $warga = Warga::find($validated['user_id']);

            if (!$warga) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Warga tidak ditemukan'
                ], 422);
            }

            /*
        |-----------------------------------------
        | CEK DUPLIKAT PENGURUS AKTIF
        |-----------------------------------------
        */
            $exists = DB::table('pengurus_wilayah')
                ->where('user_id', $validated['user_id'])
                ->where('status', 'aktif')
                ->exists();

            if ($exists) {
                DB::rollBack();

                return response()->json([
                    'status'  => 'error',
                    'message' => 'User sudah menjabat sebagai pengurus aktif'
                ], 422);
            }

            /*
        |-----------------------------------------
        | USER CREATE / UPDATE
        |-----------------------------------------
        */
            $user = User::where('warga_id', $warga->id)->first();

            if (!$user) {
                $user = User::create([
                    'warga_id'          => $warga->id,
                    'name'              => $warga->nama,
                    'email'             => $validated['email'],
                    'password'          => bcrypt('default_password'),
                    'email_verified_at' => now()
                ]);
            } else {
                $user->update([
                    'email' => $validated['email']
                ]);
            }

            /*
        |-----------------------------------------
        | ROLE CHECK
        |-----------------------------------------
        */
            $role = Role::find($validated['role_id']);

            if (!$role) {
                DB::rollBack();

                return response()->json([
                    'status'  => 'error',
                    'message' => 'Role tidak ditemukan'
                ], 422);
            }

            $user->syncRoles([$role->name]);

            /*
        |-----------------------------------------
        | INSERT PENGURUS WILAYAH
        |-----------------------------------------
        */
            DB::table('pengurus_wilayah')->insert([
                'user_id'          => $user->id,
                'role_id'          => $role->id,
                'organization_id'  => $validated['organization_id'] ?? null,
                'rw_id'            => $validated['rw_id'] ?? null,
                'rt_id'            => $validated['rt_id'] ?? null,
                'status'           => $validated['status'],

                // 🔥 JABATAN PERIOD
                'start_date'       => $validated['start_date'] ?? now(),
                'end_date'         => $validated['end_date'] ?? null,

                'created_at'       => now(),
                'updated_at'       => now(),
            ]);

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Akses user berhasil ditambahkan'
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan sistem',
                // 🔥 optional debug (hapus di production)
                // 'error' => $e->getMessage()
            ], 500);
        }
    }

    public function DeleteAkses(string $id)
    {
        $data = PengurusWilayah::find($id);

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'DATA TIDAK DITEMUKAN'
            ]);
        }

        $data->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'TERHAPUS',
            'id' => $id
        ]);
    }


    public function toggleStatus(string $id)
    {
        $data = PengurusWilayah::find($id);

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $now = now();

        // ===========================
        // STATUS TOGGLE LOGIC
        // ===========================
        if ($data->status === 'aktif') {

            $data->status = 'nonaktif';

            // 🔥 SET END DATE SAAT DINONAKTIFKAN
            $data->end_date = $now;
        } else {

            $data->status = 'aktif';

            // 🔥 RESET END DATE KALAU DIHIDUPKAN LAGI
            $data->end_date = null;

            // 🔥 SET START DATE JIKA BELUM ADA
            if (!$data->start_date) {
                $data->start_date = $now;
            }
        }

        $data->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Status berhasil diubah',
            'new_status' => $data->status,
            'start_date' => $data->start_date,
            'end_date' => $data->end_date,
        ]);
    }

    public function updateUserAkses(Request $request, string $id)
    {
        $request->validate([
            'user_id' => 'required',
            'role_id' => 'required',
            'status' => 'required',
        ]);

        $data = PengurusWilayah::findOrFail($id);

        $data->update([
            'user_id' => $request->user_id,
            'role_id' => $request->role_id,
            'organization_id' => $request->organization_id,
            'rw_id' => $request->rw_id,
            'rt_id' => $request->rt_id,
            'status' => $request->status,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return redirect()->back()->with('success', 'Data berhasil diupdate');
    }
}
