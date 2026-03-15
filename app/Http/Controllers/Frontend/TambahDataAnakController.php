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
use Illuminate\Support\Facades\Crypt;


class TambahDataAnakController extends Controller
{

    protected ImageManager $imageManager;

    public function __construct()
    {
        // Inisialisasi Image Manager sekali saja
        $this->imageManager = new ImageManager(new Driver());
    }


    public function index(Request $request)
    {
        $rumahId = session('rumah_id');

        if (!$rumahId) {
            abort(403, 'Session rumah tidak valid');
        }

        // decrypt KK id
        $keluarga_id = $request->kk_id ?? null;
        if ($keluarga_id) {
            try {
                $keluarga_id = Crypt::decryptString($keluarga_id);
            } catch (\Exception $e) {
                abort(404, 'KK tidak valid');
            }
        }

        // Debug
        // dd([
        //     'kk_id_param_terenkripsi' => $request->kk_id,
        //     'kk_id_decrypted' => $keluarga_id,
        //     'rumah_session' => $rumahId
        // ]);

        // Pastikan KK milik rumah yang login
        $keluarga = Keluarga::where('id', $keluarga_id)
            ->where('rumah_id', $rumahId)
            ->firstOrFail();

        return view('frontend.management.tambah_kk_beda.tambah_data_anak', compact('keluarga'));
    }

    /* ========================================================
   SIMPAN DATA ANAK
   - Validasi input sesuai Blade form
   - Validasi conditional: umur <17 -> NIK, HP, KTP optional
   - Sanitasi input untuk mencegah XSS
   - Gunakan transaction DB untuk konsistensi
   - Proses foto menggunakan Intervention Image
   - Response JSON untuk AJAX submit
======================================================== */
    public function store(Request $request)
    {
        $rumahId = session('rumah_id');

        // ================================
        // 1️⃣ VALIDASI INPUT DEFAULT
        // ================================
        $rules = [
            'keluarga_id' => [
                'required',
                'exists:keluargas,id',
                function ($attribute, $value, $fail) use ($rumahId) {
                    if (!Keluarga::where('id', $value)->where('rumah_id', $rumahId)->exists()) {
                        $fail('KK tidak valid untuk rumah ini.');
                    }
                }
            ],
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'hubungan' => 'required|string|max:50',
            'status_perkawinan' => 'nullable|string|max:50',
            'agama' => 'required|string|max:50',
            'pendidikan' => 'required|string|max:100',
            'provinsi' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:20480',
            'email' => 'nullable|email|max:255',
        ];

        // ================================
        // 2️⃣ VALIDASI CONDITIONAL BERDASARKAN UMUR
        // ================================
        $umur = null;
        if ($request->tanggal_lahir) {
            try {
                $tanggalLahir = new \DateTime($request->tanggal_lahir);
                $umur = $tanggalLahir->diff(new \DateTime())->y;
            } catch (\Exception $e) {
                $umur = null;
            }
        }

        if ($umur !== null && $umur >= 17) {
            $rules['nik'] = 'required|string|max:20|unique:wargas,nik';
            $rules['no_hp'] = 'required|string|max:20';
            $rules['foto_ktp'] = 'required|image|mimes:jpg,jpeg,png|max:20480';
        } else {
            $rules['nik'] = 'nullable|string|max:20|unique:wargas,nik';
            $rules['no_hp'] = 'nullable|string|max:20';
            $rules['foto_ktp'] = 'nullable|image|mimes:jpg,jpeg,png|max:20480';
        }

        $request->validate($rules);

        // ================================
        // 3️⃣ SANITASI INPUT
        // ================================
        $nama = strip_tags($request->nama);
        $tempatLahir = strip_tags($request->tempat_lahir);
        $provinsi = strip_tags($request->provinsi);
        $pekerjaan = strip_tags($request->pekerjaan ?? '');
        $agama = strip_tags($request->agama);
        $pendidikan = strip_tags($request->pendidikan);
        $hubungan = strip_tags($request->hubungan);

        // ================================
        // 4️⃣ MULAI TRANSACTION
        // ================================
        DB::beginTransaction();

        try {
            $timestamp = now()->timestamp;
            $random = mt_rand(1000, 9999);
            $namaSlug = Str::slug($nama);

            $basePath = public_path('frontend/data_warga/ktp');
            if (!File::exists($basePath)) {
                File::makeDirectory($basePath, 0755, true);
            }

            // ================================
            // 5️⃣ PROSES FOTO KTP & SELFIE
            // ================================
            $fotoKtpDb = $request->hasFile('foto_ktp')
                ? $this->processImage($request->file('foto_ktp'), $basePath, $namaSlug . '_KTP_' . $timestamp . '_' . $random)
                : null;

            $fotoSelfieDb = $this->processImage($request->file('foto'), $basePath, $namaSlug . '_SELFIE_' . $timestamp . '_' . $random);

            // ================================
            // 6️⃣ SIMPAN DATA
            // ================================
            $warga = Warga::create([
                'keluarga_id' => $request->keluarga_id,
                'nik' => $request->nik,
                'nama' => $nama,
                'jenis_kelamin' => $request->jenis_kelamin,
                'hubungan' => $hubungan,
                'status_perkawinan' => $request->status_perkawinan,
                'agama' => $agama,
                'pendidikan' => $pendidikan,
                'tanggal_lahir' => $request->tanggal_lahir,
                'province' => $provinsi,
                'tempat_lahir' => $tempatLahir,
                'pekerjaan' => $pekerjaan,
                'no_hp' => $request->no_hp,
                'email' => $request->email,
                'golongan_darah' => $request->golongan_darah,
                'foto_ktp' => $fotoKtpDb,
                'foto' => $fotoSelfieDb,
                'status' => 'aktif',
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Data anak berhasil disimpan',
                'warga_id' => $warga->id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan data anak'
            ], 500);
        }
    }
    /*
    ========================================
    HELPER: PROCESS IMAGE
    ========================================
    */
    protected function processImage($file, $path, $filenameBase): string
    {
        $manager = $this->imageManager;
        $image = $manager->read($file->getRealPath())->orient();

        // Proteksi resolusi ekstrem
        if ($image->width() > 5000 || $image->height() > 5000) {
            $image = $image->scale(width: 2000);
        }

        // Resize foto
        if ($image->width() > 1024) {
            $image = $image->scaleDown(width: 1024);
        }

        // Simpan file
        $filename = $filenameBase . '.jpg';
        $image->toJpeg(70)->save($path . '/' . $filename);

        return 'frontend/data_warga/ktp/' . $filename;
    }
}
