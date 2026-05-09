<?php

namespace App\Http\Controllers\Management\Roles;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rw;
use App\Models\Rt;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class ManagementRolseController extends Controller
{
    // ===========================
    // INDEX (ANTI N+1 + OPTIMIZED)
    // ===========================


    public function index()
    {
        // ================= ROLES =================
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


        // ================= PENGURUS WILAYAH =================
        // ================= USER LOGIN =================
        /** @var User $user */
        $user = Auth::user();
        if ($user->hasRole('super_admin')) {
            $myPengurus = null;
        }

        // ================= CEK PENGURUS LOGIN =================
        $myPengurus = DB::table('pengurus_wilayah')
            ->where('user_id', $user->id)
            ->where('status', 'aktif')
            ->first();

        // ================= PENGURUS WILAYAH =================
        $pengurus = \App\Models\PengurusWilayah::query()
            ->select([
                'id',
                'user_id',
                'role_id',
                'rt_id',
                'rw_id',
                'status',
                'created_at'
            ])
            ->where('status', 'aktif')

            // 🔥 FILTER: exclude super_admin
            ->whereHas('role', function ($q) {
                $q->where('name', '!=', 'super_admin');
            })

            // ================= FILTER BERDASARKAN RT LOGIN =================
            ->when($myPengurus && $myPengurus->rt_id !== null, function ($q) use ($myPengurus) {

                // Jika login sebagai RT
                $q->where('rt_id', $myPengurus->rt_id);
            })

            // Jika rt_id null = pengurus RW
            // tidak difilter → tampil semua

            ->with([

                // USER
                'user:id,warga_id,name,email',

                // WARGA
                'user.warga:id,keluarga_id,nama,no_hp,foto',

                // KELUARGA
                'user.warga.keluarga:id,rumah_id',

                // RUMAH
                'user.warga.keluarga.rumah:id,nomor_rumah',

                // RELASI
                'role:id,name',
                'rt:id,nama_rt',
                'rw:id,nama_rw',
            ])

            ->latest()
            ->get();

        // ================= 🔥 WARGA UNTUK MODAL =================
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

        $rws = Rw::query()
            ->select('id', 'nama_rw')
            ->where('status', 'aktif')
            ->get();

        $rts = Rt::query()
            ->select('id', 'rw_id', 'nama_rt')
            ->where('status', 'aktif')
            ->get();

        return view('backend.management.roles.index', compact(
            'roles',
            'pengurus',
            'wargas',
            'rws',
            'rts'
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
            'user_id' => ['required', 'exists:wargas,id'],
            'email'   => ['required', 'email'],
            'role_id' => ['required', 'exists:roles,id'],
            'rw_id'   => ['nullable', 'exists:rws,id'],
            'rt_id'   => ['nullable', 'exists:rts,id'],
            'status'  => ['required', 'in:aktif,nonaktif'],
        ]);

        try {

            DB::beginTransaction();

            // ===========================
            // 1. AMBIL DATA WARGA
            // ===========================
            $warga = Warga::findOrFail($validated['user_id']);

            // ===========================
            // 2. CEK USER BERDASARKAN warga_id
            // ===========================
            $user = User::where('warga_id', $warga->id)->first();

            if (!$user) {

                // ===========================
                // 3. BUAT USER BARU
                // ===========================
                $user = User::create([
                    'warga_id' => $warga->id,
                    'name'     => $warga->nama,
                    'email'    => $validated['email'],
                    'password' => bcrypt('ManagementCitraSwarnaRiverside_RW016'),
                    'email_verified_at' => now()
                ]);
            } else {

                // ===========================
                // 4. UPDATE EMAIL (JIKA SUDAH ADA USER)
                // ===========================
                $user->update([
                    'email' => $validated['email']
                ]);
            }

            // ===========================
            // 5. ASSIGN ROLE (SPATIE)
            // ===========================
            $role = Role::findById($validated['role_id']);
            $user->syncRoles([$role->name]);

            // ===========================
            // 6. CEK DUPLIKASI PENGURUS
            // ===========================
            $cekPengurus = DB::table('pengurus_wilayah')
                ->where('user_id', $user->id)
                ->exists();

            if ($cekPengurus) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User sudah terdaftar sebagai pengurus'
                ], 422);
            }

            // ===========================
            // 7. SIMPAN KE PENGURUS WILAYAH
            // ===========================
            DB::table('pengurus_wilayah')->insert([
                'user_id' => $user->id,
                'role_id' => $validated['role_id'],
                'rw_id'   => $validated['rw_id'],
                'rt_id'   => $validated['rt_id'],
                'status'  => $validated['status'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Akses user berhasil ditambahkan'
            ]);
        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage() // debug dulu
            ], 500);
        }
    }
}
