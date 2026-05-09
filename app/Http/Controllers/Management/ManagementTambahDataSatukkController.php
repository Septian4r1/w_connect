<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Warga;
use App\Models\Keluarga;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ManagementTambahDataSatukkController extends Controller
{
    protected ImageManager $imageManager;

    public function __construct()
    {
        $this->imageManager = new ImageManager(new Driver());
    }

    // =========================
    // FORM CREATE
    // =========================
    public function create($id)
    {
        try {
            $wargaId = Crypt::decrypt($id);
        } catch (\Exception $e) {
            abort(404);
        }

        $warga = Warga::with(['keluarga.rumah'])->findOrFail($wargaId);

        $keluargaIdEncrypted = Crypt::encrypt($warga->keluarga->id);

        return view('backend.management.warga.tambah_dataKeluarga', compact('warga', 'keluargaIdEncrypted'));
    }

    // =========================
    // STORE DATA WARGA
    // =========================
    public function storDataSatuKK(Request $request)
    {
        // =========================
        // 1. DECRYPT & VALIDASI KK
        // =========================
        try {
            $keluarga_id = Crypt::decrypt($request->keluarga_id);
        } catch (\Exception $e) {
            return $this->errorResponse('KK tidak valid', 400);
        }

        $keluarga = Keluarga::find($keluarga_id);
        if (!$keluarga) {
            return $this->errorResponse('Data KK tidak ditemukan', 404);
        }

        // =========================
        // 2. VALIDASI INPUT (FOTO SUDAH NULLABLE)
        // =========================
        $rules = [
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'hubungan' => 'required|string|max:50',
            'agama' => 'required|string|max:50',
            'pendidikan' => 'required|string|max:100',
            'provinsi' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',

            // ✅ PERBAIKAN DI SINI
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:20480',
        ];

        // =========================
        // 3. VALIDASI UMUR
        // =========================
        $umur = (new \DateTime($request->tanggal_lahir))
            ->diff(new \DateTime())->y;

        if ($umur >= 17) {
            $rules['nik'] = 'required|digits:16|unique:wargas,nik';
            $rules['no_hp'] = 'nullable|string|max:20';
            $rules['foto_ktp'] = 'nullable|image|mimes:jpg,jpeg,png|max:20480';
        } else {
            $rules['nik'] = 'nullable|digits:16|unique:wargas,nik';
        }

        $validator = validator($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'validation',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // =========================
        // 4. TRANSACTION
        // =========================
        DB::beginTransaction();

        try {

            // =========================
            // SANITASI INPUT
            // =========================
            $nama = strip_tags($request->nama);
            $tempat_lahir = strip_tags($request->tempat_lahir);
            $provinsi = strip_tags($request->provinsi);

            $namaSlug = Str::slug($nama);
            $timestamp = now()->timestamp;
            $random = rand(1000, 9999);

            $basePath = public_path('backend/data_warga');

            if (!File::exists($basePath)) {
                File::makeDirectory($basePath, 0755, true);
            }

            // =========================
            // UPLOAD FOTO (SUDAH OPTIONAL)
            // =========================
            $foto = $request->hasFile('foto')
                ? $this->processImage(
                    $request->file('foto'),
                    $basePath,
                    "{$namaSlug}_SELFIE_{$timestamp}_{$random}"
                )
                : null;

            $fotoKtp = $request->hasFile('foto_ktp')
                ? $this->processImage(
                    $request->file('foto_ktp'),
                    $basePath,
                    "{$namaSlug}_KTP_{$timestamp}_{$random}"
                )
                : null;

            // =========================
            // SIMPAN DATA
            // =========================
            Warga::create([
                'keluarga_id' => $keluarga_id,
                'nik' => $request->nik,
                'nama' => $nama,
                'jenis_kelamin' => $request->jenis_kelamin,
                'hubungan' => $request->hubungan,
                'status_perkawinan' => $request->status_perkawinan,
                'agama' => $request->agama,
                'pendidikan' => $request->pendidikan,
                'tanggal_lahir' => $request->tanggal_lahir,
                'province' => $provinsi,
                'tempat_lahir' => $tempat_lahir,
                'pekerjaan' => $request->pekerjaan,
                'no_hp' => $request->no_hp,
                'email' => $request->email,
                'golongan_darah' => $request->golongan_darah,

                // ✅ sekarang aman nullable
                'foto' => $foto,
                'foto_ktp' => $fotoKtp,

                'status' => 'aktif',
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Data warga berhasil disimpan',
                'redirect' => route('management.warga.index')
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return $this->errorResponse(
                config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan server',
                500
            );
        }
    }

    // =========================
    // HELPER IMAGE
    // =========================
    protected function processImage($file, $path, $filenameBase)
    {
        $image = $this->imageManager->read($file->getRealPath())->orient();

        if ($image->width() > 1024) {
            $image = $image->scaleDown(width: 1024);
        }

        $filename = $filenameBase . '.jpg';
        $image->toJpeg(70)->save($path . '/' . $filename);

        return 'backend/data_warga/' . $filename;
    }

    // =========================
    // RESPONSE STANDARD
    // =========================
    protected function errorResponse($message, $code = 400)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message
        ], $code);
    }
}
