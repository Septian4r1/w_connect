<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Warga;
use App\Models\Keluarga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ManagementTambahKeluargaBedaKKController extends Controller
{
    protected ImageManager $imageManager;

    public function __construct()
    {
        $this->imageManager = new ImageManager(new Driver());
    }

    /*
    =========================
    SHOW FORM
    =========================
    */
    public function create($id)
    {
        return view(
            'backend.management.warga.tambah_datawarga_bedaKK',
            [
                // 🔥 kirim tetap encrypted ke blade
                'keluarga_id' => $id
            ]
        );
    }

    /*
    =========================
    STORE DATA WARGA
    =========================
    */
    public function DataWargaBedakkStore(Request $request)
    {
        $request->validate([
            'keluarga_id'   => 'required',
            'nik'           => 'required|string|max:20|unique:wargas,nik',
            'nama'          => 'required|string|max:255',
            'jenis_kelamin' => 'required|string',
            'hubungan'      => 'required|string',
            'agama'         => 'required|string',
            'pendidikan'    => 'required|string',
            'provinsi'      => 'required|string|max:255',
            'tempat_lahir'  => 'required|string|max:255',
            'foto_ktp'      => 'nullable|image|max:20480',
            'foto'          => 'nullable|image|max:20480',
        ]);



        DB::beginTransaction();

        try {

            /*
            =========================
            DECRYPT keluarga_id
            =========================
            */
            $keluargaId = Crypt::decryptString($request->keluarga_id);

            //dd($keluargaId);

            /*
        =====================================================
        VALIDASI MANUAL (BIAR TIDAK ERROR 500)
        =====================================================
        - jika data tidak ditemukan → return JSON error
        */
            $keluarga = Keluarga::find($keluargaId);

            if (!$keluarga) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Data keluarga tidak ditemukan'
                ], 404);
            }

            /*
        =====================================================
        3. PREPARASI FILE IMAGE
        =====================================================
        */
            $now = now()->format('dmY_His');
            $namaSlug = Str::slug($request->nama);

            $basePath = public_path('frontend/data_warga/ktp');

            if (!File::exists($basePath)) {
                File::makeDirectory($basePath, 0755, true);
            }

            /*
            =========================
            FOTO KTP
            =========================
            */
            $fotoKtpDb = null;
            if ($request->hasFile('foto_ktp')) {
                $fotoKtpDb = $this->processImage(
                    $request->file('foto_ktp'),
                    $basePath,
                    $namaSlug . '_KTP_' . $now
                );
            }

            /*
            =========================
            FOTO SELFIE
            =========================
            */
            $fotoSelfieDb = null;
            if ($request->hasFile('foto')) {
                $fotoSelfieDb = $this->processImage(
                    $request->file('foto'),
                    $basePath,
                    $namaSlug . '_SELFIE_' . $now
                );
            }

            /*
            =========================
            INSERT DATA
            =========================
            */
            $warga = Warga::create([
                'keluarga_id'       => $keluarga->id,
                'nik'               => $request->nik,
                'nama'              => $request->nama,
                'jenis_kelamin'     => $request->jenis_kelamin,
                'hubungan'          => $request->hubungan,
                'status_perkawinan' => $request->status_perkawinan,
                'agama'             => $request->agama,
                'pendidikan'        => $request->pendidikan,
                'tanggal_lahir'     => $request->tanggal_lahir,
                'province'          => $request->provinsi,
                'tempat_lahir'      => $request->tempat_lahir,
                'pekerjaan'         => $request->pekerjaan,
                'no_hp'             => $request->no_hp,
                'email'             => $request->email,
                'golongan_darah'    => $request->golongan_darah,

                'foto_ktp'          => $fotoKtpDb,
                'foto'              => $fotoSelfieDb,

                'status'            => 'aktif',
            ]);

            DB::commit();

            return response()->json([
                'status'   => 'success',
                'message'  => 'Data warga berhasil disimpan',
                'redirect' => route('management.warga.index'),
                'warga_id' => $warga->id
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    /*
    =========================
    IMAGE PROCESS
    =========================
    */
    protected function processImage($file, $path, $filenameBase): string
    {
        $image = $this->imageManager->read($file->getRealPath())
            ->orient();

        if ($image->width() > 5000 || $image->height() > 5000) {
            $image = $image->scale(width: 2000);
        }

        if ($image->width() > 1024) {
            $image = $image->scaleDown(width: 1024);
        }

        $filename = $filenameBase . '.jpg';

        $image->toJpeg(70)->save($path . '/' . $filename);

        return 'frontend/data_warga/ktp/' . $filename;
    }
}
