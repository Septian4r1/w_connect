<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Keluarga;
use App\Models\Rumah;
use App\Models\JenisKk;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Crypt;
use App\Models\PengajuanPerubahan;

class DataKeluargaController extends Controller
{

    /*
    =====================================================
    MENAMPILKAN DATA KARTU KELUARGA
    =====================================================
    Fungsi ini digunakan untuk menampilkan daftar KK
    milik rumah yang sedang login.

    Data dibagi menjadi 2 kategori:
    1. KK Utama (jenis_kk_id = 1)
    2. KK Tambahan (jenis_kk_id != 1)

    Dengan eager loading kepalaKeluarga agar query lebih efisien
    =====================================================
    */
    public function index()
    {
        $rumahId = session('rumah_id');

        // KK Utama
        $keluargaUtama = Keluarga::with(['kepalaKeluarga', 'jenisKk', 'anggota'])
            ->where('rumah_id', $rumahId)
            ->where('jenis_kk_id', 1)
            ->get();

        // KK Tambahan
        $keluargaTambahan = Keluarga::with(['kepalaKeluarga', 'anggota', 'jenisKk'])
            ->where('rumah_id', $rumahId)
            ->where('jenis_kk_id', '!=', 1)
            ->whereHas('kepalaKeluarga')
            ->get();

        // Ambil pengajuan perubahan warga untuk rumah ini
        $pengajuanList = PengajuanPerubahan::with('approvals')
            ->whereHas('warga.keluarga', function ($q) use ($rumahId) {
                $q->where('rumah_id', $rumahId);
            })
            ->latest()
            ->get();

        return view(
            'frontend.management.datakeluarga',
            compact('keluargaUtama', 'keluargaTambahan', 'pengajuanList')
        );
    }


    /*
    =====================================================
    FORM TAMBAH KK TAMBAHAN
    =====================================================
    Mengambil jenis KK selain KK utama
    =====================================================
    */
    public function tambahData_bedakk()
    {
        $jenisKk = JenisKk::where('id', '!=', 1)->get();

        return view(
            'frontend.management.tambah_kk_beda.tambah_kk',
            compact('jenisKk')
        );
    }



    /*
    =====================================================
    SIMPAN DATA KK TAMBAHAN
    =====================================================
    Fungsi ini melakukan beberapa proses penting:

    1. Validasi input
    2. Proteksi injection
    3. Upload dan kompres foto KK
    4. Simpan ke database
    5. Redirect ke input warga
    =====================================================
    */
    public function store_beda_kk(Request $request)
    {

        /*
        =====================================================
        MULAI DATABASE TRANSACTION
        =====================================================
        Digunakan agar jika terjadi error,
        semua proses database bisa dibatalkan.
        =====================================================
        */
        DB::beginTransaction();

        try {

            /*
            =====================================================
            VALIDASI INPUT
            =====================================================
            Laravel validator otomatis melindungi
            dari beberapa jenis injection.
            =====================================================
            */

            $request->validate([

                'no_kk' => 'required|max:20|unique:keluargas,no_kk',

                'jenis_kk_id' => 'required|exists:jenis_kks,id',

                'ktp_setempat' => 'required',

                'kependudukan' => 'required',

                // Upload gambar maksimal 20MB
                'foto_kk' => 'nullable|image|mimes:jpg,jpeg,png|max:20480',

            ]);


            /*
            =====================================================
            AMBIL SESSION RUMAH
            =====================================================
            */

            $rumahId = session('rumah_id');

            if (!$rumahId) {

                return back()->with([
                    'status' => 'error',
                    'message' => 'Session rumah tidak ditemukan. Silakan login ulang'
                ]);
            }


            /*
            =====================================================
            AMBIL DATA RUMAH
            =====================================================
            Hanya mengambil kolom yang dibutuhkan
            agar query lebih ringan
            =====================================================
            */

            $rumah = Rumah::select('id', 'nomor_rumah')
                ->findOrFail($rumahId);



            /*
            =====================================================
            SANITASI INPUT
            =====================================================
            Menghindari script injection
            =====================================================
            */

            $alamatKK = strip_tags($request->alamat_kk);
            $desa = strip_tags($request->desa_kelurahan);
            $kecamatan = strip_tags($request->kecamatan);
            $kota = strip_tags($request->kota);
            $provinsi = strip_tags($request->provinsi);



            /*
            =====================================================
            PROSES UPLOAD FOTO KK
            =====================================================
            */

            $foto = null;

            if ($request->hasFile('foto_kk')) {

                $file = $request->file('foto_kk');


                /*
                -------------------------------------------------
                BERSIHKAN NOMOR RUMAH
                -------------------------------------------------
                */
                $nomorRumah = str_replace('/', '-', $rumah->nomor_rumah);


                /*
                -------------------------------------------------
                BUAT NAMA FILE YANG UNIK
                -------------------------------------------------
                Digabung dengan timestamp + random number
                untuk menghindari bentrok saat upload bersamaan
                -------------------------------------------------
                */

                $kodeUnik = now()->timestamp . '_' . mt_rand(1000, 9999);

                $namaFile = $nomorRumah . '_' . $kodeUnik . '.jpg';


                /*
                -------------------------------------------------
                FOLDER PENYIMPANAN
                -------------------------------------------------
                */

                $path = public_path('frontend/data_warga/kk');

                if (!File::exists($path)) {
                    File::makeDirectory($path, 0755, true);
                }


                /*
                =====================================================
                PROSES RESIZE + COMPRESS GAMBAR
                =====================================================
                Menggunakan Intervention Image
                =====================================================
                */

                $manager = new ImageManager(new Driver());

                $image = $manager->read($file->getRealPath())
                    ->orient();


                /*
                -------------------------------------------------
                PROTEKSI FOTO RESOLUSI EKSTREM
                -------------------------------------------------
                */
                if ($image->width() > 5000 || $image->height() > 5000) {
                    $image = $image->scale(width: 2000);
                }


                /*
                -------------------------------------------------
                RESIZE JIKA TERLALU BESAR
                -------------------------------------------------
                */
                if ($image->width() > 1024) {
                    $image = $image->scaleDown(width: 1024);
                }


                /*
                -------------------------------------------------
                COMPRESS GAMBAR
                -------------------------------------------------
                */
                $image = $image->toJpeg(65);

                $image->save($path . '/' . $namaFile);


                /*
                -------------------------------------------------
                PATH UNTUK DATABASE
                -------------------------------------------------
                */

                $foto = 'frontend/data_warga/kk/' . $namaFile;
            }



            /*
            =====================================================
            SIMPAN DATA KE DATABASE
            =====================================================
            */

            $keluarga = Keluarga::create([

                'rumah_id' => $rumahId,

                'jenis_kk_id' => $request->jenis_kk_id,

                'no_kk' => $request->no_kk,

                'foto_kk' => $foto,

                'status' => $request->status ?? 'aktif',

                'ktp_setempat' => $request->ktp_setempat,

                'kependudukan' => $request->kependudukan,

                'alamat_kk' => $alamatKK,

                'desa_kelurahan' => $desa,

                'kecamatan' => $kecamatan,

                'kota_kabupaten' => $kota,

                'provinsi' => $provinsi,

            ]);



            /*
            =====================================================
            COMMIT DATABASE
            =====================================================
            */

            DB::commit();



            /*
            =====================================================
            REDIRECT KE INPUT DATA WARGA
            =====================================================
            */

            return redirect()
                ->route('warga.create', [
                    'keluarga_id' => Crypt::encryptString($keluarga->id)
                ])
                ->with([
                    'status' => 'success',
                    'message' => 'Data keluarga berhasil disimpan'
                ]);
        } catch (\Exception $e) {

            /*
            =====================================================
            ROLLBACK DATABASE JIKA ERROR
            =====================================================
            */

            DB::rollBack();

            return back()->with([
                'status' => 'error',
                'message' => 'Gagal menyimpan data : ' . $e->getMessage()
            ]);
        }
    }
}
