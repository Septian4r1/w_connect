<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Block;
use App\Models\Rumah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Crypt;


class ManagementTambahWargaController extends Controller
{
    /**
     * Opsi status hunian (✅ SUDAH SESUAI ENUM DB)
     */
    private $statusHunianOptions = [
        'huni milik sendiri' => 'Milik Sendiri',
        'kosong'             => 'Kosong',
        'sewa'               => 'Sewa',
        'belum huni'         => 'Belum Huni',
    ];

    /**
     * FORM
     */
    public function create()
    {
        // ✅ SELECT ONLY FIELD YANG DIPAKAI (hindari overfetch)
        $blocks = Block::query()
            ->select('id', 'nama_blok')
            ->orderBy('nama_blok')
            ->get();

        return view('backend.management.warga.register_management', [
            'blocks' => $blocks,
            'statusHunianOptions' => $this->statusHunianOptions,
        ]);
    }

    /**
     * STORE
     */
    public function store_management_warga(Request $request)
    {
        // ================= VALIDASI + SANITIZE
        $validated = $request->validate([
            'block_id' => ['required', 'integer', 'exists:blocks,id'],

            'nomor_rumah' => [
                'required',
                'string',
                'max:20',
                Rule::unique('rumahs')->where(
                    fn($q) =>
                    $q->where('block_id', $request->block_id)
                ),
            ],

            'alamat_lengkap' => ['nullable', 'string', 'max:255'],

            'status_hunian' => [
                'required',
                Rule::in(array_keys($this->statusHunianOptions))
            ],

        ], [
            // 🔥 custom message
            'nomor_rumah.unique' => 'Nomor rumah sudah digunakan di blok ini',
        ]);
        // ================= SANITIZE INPUT (XSS PROTECTION)
        $validated['nomor_rumah'] = trim(strip_tags($validated['nomor_rumah']));
        $validated['alamat_lengkap'] = isset($validated['alamat_lengkap'])
            ? trim(strip_tags($validated['alamat_lengkap']))
            : null;

        try {
            DB::beginTransaction();

            // ================= LOCK ROW (ANTI RACE CONDITION 🔥)
            $exists = Rumah::where('block_id', $validated['block_id'])
                ->where('nomor_rumah', $validated['nomor_rumah'])
                ->lockForUpdate()
                ->exists();

            if ($exists) {
                return $this->responseError($request, 'Nomor rumah sudah terdaftar di blok ini');
            }

            // ================= INSERT (SAFE ELOQUENT)
            // ================= AMBIL NOMOR RUMAH APA ADANYA
            $nomorRumah = trim($validated['nomor_rumah']);

            // optional: rapikan spasi (biar ga ada spasi dobel)
            $nomorRumah = preg_replace('/\s+/', '', $nomorRumah); // 🔥 ini penting biar "H3/31" tetap bersih

            // ================= PASSWORD SESUAI FORMAT ASLI
            $passwordPlain = 'CitraSwarnaRiverside_' . $nomorRumah;

            // ================= SIMPAN
            $rumah = Rumah::create([
                'block_id'       => $validated['block_id'],
                'nomor_rumah'    => $nomorRumah,
                'alamat_lengkap' => $validated['alamat_lengkap'],
                'desa'           => 'Bojong',
                'kelurahan'      => 'Klapanunggal',
                'kode_pos'       => '16710',
                'status_hunian'  => $validated['status_hunian'],
                'password'       => $passwordPlain,
                'status_login'   => 'offline',
                'layanan_approval' => 1,
            ]);

            session([
                'rumah_id' => $rumah->id,
                'generated_password' => $passwordPlain // 🔥 simpan untuk ditampilkan
            ]);

            DB::commit();
            return $this->responseSuccess($request, 'Data berhasil disimpan', $rumah);
        } catch (QueryException $e) {

            DB::rollBack();

            if ($e->getCode() === '23000') {
                return $this->responseError($request, 'Data sudah ada (duplikat)');
            }

            throw $e;
        }
    }

    /**
     * RESPONSE SUCCESS
     */
    private function responseSuccess($request, $message, $rumah)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => e($message),
                'redirect' => route('management.warga.tambah_keluarga', [
                    'rumah_id' => Crypt::encryptString($rumah->id)
                ])
            ]);
        }

        return redirect()
            ->route('management.warga.tambah_keluarga', [
                'rumah_id' => Crypt::encryptString($rumah->id)
            ])
            ->with('success', $message);
    }
    /**
     * RESPONSE ERROR
     */
    private function responseError($request, $message)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'error',
                'message' => e($message)
            ], 422);
        }

        return back()
            ->with('error', $message)
            ->withInput();
    }
}
