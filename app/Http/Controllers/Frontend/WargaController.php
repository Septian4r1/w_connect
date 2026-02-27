<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Warga;
use App\Models\Keluarga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class WargaController extends Controller
{
    protected ImageManager $imageManager;

    public function __construct()
    {
        // Inisialisasi sekali untuk reuse
        $this->imageManager = new ImageManager(new Driver());
    }

    /*
    =========================
    FORM CREATE
    =========================
    */
    public function create()
    {
        $rumahId = session('rumah_id');

        $keluarga = Keluarga::where('rumah_id', $rumahId)
            ->first(['id']);

        return view('frontend.data_warga.create_warga', compact('keluarga'));
    }

    /*
    =========================
    SIMPAN DATA WARGA
    =========================
    */
    public function store(Request $request)
    {
        $request->validate([
            'keluarga_id' => 'required|exists:keluargas,id',
            'nik' => 'required|string|max:20|unique:wargas,nik',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|string',
            'hubungan' => 'required|string',
            'agama' => 'required|string',
            'pendidikan' => 'required|string',
            'provinsi' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'foto_ktp' => 'required|image|max:20480',
            'foto' => 'required|image|max:20480',
        ]);

        DB::beginTransaction();

        try {
            $now = now()->format('dmY_His');
            $namaSlug = Str::slug($request->nama);

            $basePath = public_path('frontend/data_warga/ktp');

            if (!File::exists($basePath)) {
                File::makeDirectory($basePath, 0755, true);
            }

            // proses foto
            $fotoKtpDb = $this->processImage($request->file('foto_ktp'), $basePath, $namaSlug . '_KTP_' . $now);
            $fotoSelfieDb = $this->processImage($request->file('foto'), $basePath, $namaSlug . '_SELFIE_' . $now);

            // simpan database
            $warga = Warga::create([
                'keluarga_id' => $request->keluarga_id,
                'nik' => $request->nik,
                'nama' => $request->nama,
                'jenis_kelamin' => $request->jenis_kelamin,
                'hubungan' => $request->hubungan,
                'status_perkawinan' => $request->status_perkawinan,
                'agama' => $request->agama,
                'pendidikan' => $request->pendidikan,
                'tanggal_lahir' => $request->tanggal_lahir,
                'province' => $request->provinsi,
                'tempat_lahir' => $request->tempat_lahir,
                'pekerjaan' => $request->pekerjaan,
                'no_hp' => $request->no_hp,
                'email' => $request->email,
                'golongan_darah' => $request->golongan_darah,
                'foto_ktp' => $fotoKtpDb,
                'foto' => $fotoSelfieDb,
                'status' => 'aktif',
            ]);

            DB::commit();

            $action = $request->input('action_type', 'close');

            return response()->json([
                'status' => 'success',
                'message' => 'Data warga berhasil disimpan',
                'action' => $action,
                'warga_id' => $warga->id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    /*
    =========================
    HELPER: PROCESS IMAGE
    =========================
    */
    protected function processImage($file, $path, $filenameBase): string
    {
        $manager = $this->imageManager;

        $image = $manager->read($file->getRealPath())
            ->orient(); // auto rotate EXIF

        // proteksi resolusi ekstrem
        if ($image->width() > 5000 || $image->height() > 5000) {
            $image = $image->scale(width: 2000);
        }

        // resize hanya jika lebar >1024
        if ($image->width() > 1024) {
            $image = $image->scaleDown(width: 1024);
        }

        $filename = $filenameBase . '.jpg';
        $image->toJpeg(70)->save($path . '/' . $filename);

        return 'frontend/data_warga/ktp/' . $filename;
    }
}
