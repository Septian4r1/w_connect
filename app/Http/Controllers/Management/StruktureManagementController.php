<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Models\Rt;
use App\Models\Rw;
use App\Models\Block;
use Illuminate\Support\Facades\Validator;

class StruktureManagementController extends Controller
{
    public function index(Request $request)
    {
        // ================= FILTER (AMAN) =================
        $status = $request->input('status');
        $allowedStatus = ['aktif', 'nonaktif'];

        if (!in_array($status, $allowedStatus)) {
            $status = null; // fallback aman
        }

        // ================= RW =================
        $rws = Rw::query()
            ->select(['id', 'nama_rw'])
            ->when($status, fn($q) => $q->where('status', $status))
            ->where('status', 'aktif')
            ->orderBy('nama_rw')
            ->get();

        // ================= RT =================
        $rts = Rt::query()
            ->with([
                'rw:id,nama_rw' // ✅ cegah N+1
            ])
            ->select(['id', 'rw_id', 'nama_rt', 'status'])
            ->when($status, fn($q) => $q->where('status', $status))
            ->orderBy('nama_rt')
            ->paginate(10, ['*'], 'rt_page')
            ->withQueryString(); // ✅ aman untuk pagination + filter

        // ================= BLOCK =================
        $blocks = Rt::query()
            ->with([
                'rw:id,nama_rw',
                'blocks:id,rt_id,nama_blok,status'
            ])
            ->select(['id', 'rw_id', 'nama_rt'])
            ->when($status, fn($q) => $q->where('status', $status))
            ->orderBy('nama_rt')
            ->paginate(4, ['*'], 'block_page') // 🔥 FIX 4 DATA SAJA
            ->withQueryString();

        $blockList = Block::with([
            'rt:id,nama_rt,rw_id',
            'rt.rw:id,nama_rw'
        ])
            ->select(['id', 'rt_id', 'nama_blok', 'status'])
            ->orderBy('id', 'desc')
            ->get(); // ❗ JANGAN paginate lagi

        // ================= RT ALL =================
        $rts_all = Rt::query()
            ->select(['id', 'rw_id', 'nama_rt'])
            ->where('status', 'aktif')
            ->orderBy('nama_rt')
            ->get();

        return view('backend.management.area_management.index_area', compact(
            'rws',
            'rts',
            'blocks',
            'blockList',
            'rts_all'
        ));
    }

    // ================= STORE RT =================
    public function store_RT(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'rw_id'    => ['required', 'integer', 'exists:rws,id'],
            'nama_rt'  => ['required', 'string', 'max:10'],
            'status'   => ['required', 'in:aktif,nonaktif'],
        ]);

        if ($validated->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validated->errors()->first()
            ], 422);
        }

        try {
            // ✅ Sanitasi input (anti XSS)
            $nama_rt = htmlspecialchars(
                trim($request->nama_rt),
                ENT_QUOTES,
                'UTF-8'
            );

            Rt::create([
                'rw_id'   => (int) $request->rw_id, // ✅ casting anti injection
                'nama_rt' => $nama_rt,
                'status'  => $request->status,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'RT berhasil ditambahkan!'
            ]);
        } catch (\Exception $e) {
            report($e); // ✅ jangan expose error ke user

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan pada server'
            ], 500);
        }
    }

    public function store_Block(Request $request)
    {
        try {

            // ================= NORMALISASI INPUT =================
            $nama_blok = trim($request->nama_blok);
            $nama_blok = preg_replace('/\s+/u', ' ', $nama_blok);
            $nama_blok = strtoupper($nama_blok);
            $nama_blok = str_replace(' ', '_', $nama_blok);

            if (!str_starts_with($nama_blok, 'BLOK_')) {
                $nama_blok = 'BLOK_' . $nama_blok;
            }

            // ================= VALIDASI =================
            $validated = Validator::make($request->all(), [
                'rw_id'  => ['required', 'integer', 'exists:rws,id'],
                'rt_id'  => ['required', 'integer', 'exists:rts,id'],
                'status' => ['required', 'in:aktif,nonaktif'],

                // 🔥 INI KUNCI UTAMA
                'nama_blok' => [
                    'required',
                    'string',
                    'max:50',
                    function ($attribute, $value, $fail) use ($nama_blok) {
                        $exists = \App\Models\Block::where('nama_blok', $nama_blok)->exists();

                        if ($exists) {
                            $fail('Nama blok ini sudah digunakan di RT lain!');
                        }
                    }
                ],
            ]);

            if ($validated->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validated->errors()->first()
                ], 422);
            }

            // ================= INSERT =================
            \App\Models\Block::create([
                'rt_id'     => (int) $request->rt_id,
                'nama_blok' => $nama_blok,
                'status'    => $request->status,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Block berhasil ditambahkan!'
            ]);
        } catch (\Exception $e) {
            report($e);

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan pada server'
            ], 500);
        }
    }
    public function updateBlock(Request $request, string $id)
    {
        try {

            // DECRYPT
            $realId = Crypt::decrypt($id);

            $block = Block::find($realId);

            if (!$block) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data block tidak ditemukan'
                ], 404);
            }

            // VALIDASI
            $validated = $request->validate([
                'nama_blok' => 'required|string|max:50',
                'status'    => 'required|in:aktif,nonaktif',
                'rt_id'     => 'required|exists:rts,id',
            ]);

            // NORMALISASI
            $nama_blok = trim($validated['nama_blok']);
            $nama_blok = preg_replace('/\s+/u', ' ', $nama_blok);
            $nama_blok = strtoupper($nama_blok);
            $nama_blok = str_replace(' ', '_', $nama_blok);

            if (!str_starts_with($nama_blok, 'BLOK_')) {
                $nama_blok = 'BLOK_' . $nama_blok;
            }

            // DUPLIKAT CHECK
            $exists = Block::where('nama_blok', $nama_blok)
                ->where('id', '!=', $block->id)
                ->exists();

            if ($exists) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Nama block sudah digunakan!'
                ], 422);
            }

            // UPDATE
            $block->update([
                'nama_blok' => $nama_blok,
                'status'    => $validated['status'],
                'rt_id'     => (int) $validated['rt_id'],
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Block berhasil diupdate!'
            ]);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage() // 🔥 DEBUG MODE
            ], 500);
        }
    }
}
