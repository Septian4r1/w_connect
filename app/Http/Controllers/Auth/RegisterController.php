<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Block;
use App\Models\Rumah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;

class RegisterController extends Controller
{
    /**
     * Opsi status hunian sesuai enum DB
     */
    private $statusHunianOptions = [
        'huni milik sendiri' => 'Huni Milik Sendiri',
        'kosong'             => 'Kosong',
        'sewa'               => 'Sewa',
        'belum huni'         => 'Belum Huni',
    ];

    /**
     * Tampilkan form register
     */
    public function showRegister()
    {
        // Ambil blok aktif saja, cukup id & nama
        $blocks = Block::active()
            ->orderBy('nama_blok')
            ->get(['id', 'nama_blok']);

        return view('frontend.auth.register', [
            'blocks' => $blocks,
            'statusHunianOptions' => $this->statusHunianOptions,
        ]);
    }

    /**
     * Proses register rumah baru
     */
    public function register(Request $request)
    {
        // 1️⃣ Validasi input
        $validated = $request->validate([
            'block_id'       => ['required', 'exists:blocks,id'],
            'nomor_rumah'    => ['required', 'string', 'max:20'],
            'alamat_lengkap' => ['nullable', 'string'],
            'status_hunian'  => ['required', Rule::in(array_keys($this->statusHunianOptions))],
            'password'       => ['required', 'confirmed', Password::defaults()],
        ]);

        try {
            // 2️⃣ Gunakan transaksi singkat untuk keamanan data
            DB::beginTransaction();

            // 3️⃣ Cek nomor rumah unik dengan cepat (ambil 1 record saja)
            $existing = Rumah::where('nomor_rumah', $validated['nomor_rumah'])
                ->select('id')
                ->first();

            if ($existing) {
                $message = 'Nomor rumah sudah terdaftar';

                if ($request->ajax()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $message
                    ], 422);
                }

                return back()->with('error', $message)->withInput();
            }

            // 4️⃣ Simpan data rumah baru
            // Password otomatis di-hash via mutator di model Rumah
            Rumah::create([
                'block_id'       => $validated['block_id'],
                'nomor_rumah'    => $validated['nomor_rumah'],
                'alamat_lengkap' => $validated['alamat_lengkap'] ?? null,
                'desa'           => 'Bojong',           // hardcoded, bisa diubah sesuai kebutuhan
                'kelurahan'      => 'Klapanunggal',     // hardcoded
                'kode_pos'       => '16710',            // hardcoded
                'status_hunian'  => $validated['status_hunian'],
                'password'       => $validated['password'],
                'status_login'   => 'offline',
            ]);

            DB::commit(); // ✅ commit transaksi

            // 5️⃣ Response
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Registrasi rumah berhasil. Silakan login.',
                    'redirect' => route('showlogin')
                ]);
            }

            return redirect()->route('showlogin')
                ->with('success', 'Registrasi rumah berhasil. Silakan login.');
        } catch (QueryException $e) {
            DB::rollBack(); // rollback otomatis bila error

            // 6️⃣ Tangani duplicate key secara aman
            if ($e->getCode() === '23000') {
                $message = 'Nomor rumah sudah terdaftar, silakan pilih yang lain';

                if ($request->ajax()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $message
                    ], 422);
                }

                return back()->with('error', $message)->withInput();
            }

            // Lainnya: lempar exception
            throw $e;
        }
    }
}
