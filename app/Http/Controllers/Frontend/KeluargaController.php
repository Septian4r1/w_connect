<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Keluarga;
use App\Models\Rumah;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Crypt;

class KeluargaController extends Controller
{

    /*
    =====================================================
    FORM CREATE DATA KELUARGA
    =====================================================
    Menampilkan form input KK utama
    =====================================================
    */
    public function create()
    {
        return view('frontend.data_warga.create_keluargas');
    }



    /*
    =====================================================
    SIMPAN DATA KELUARGA UTAMA
    =====================================================
    Proses:
    1. Validasi input
    2. Sanitasi input (mencegah script injection)
    3. Upload & kompres foto KK
    4. Simpan ke database
    5. Redirect ke input warga
    =====================================================
    */
    public function store(Request $request)
    {

        /*
        =====================================================
        DATABASE TRANSACTION
        =====================================================
        Agar jika terjadi error semua proses dibatalkan
        =====================================================
        */

        DB::beginTransaction();

        try {

            /*
            =====================================================
            VALIDASI INPUT
            =====================================================
            */

            $request->validate([

                'no_kk' => 'required|string|max:20',

                'ktp_setempat' => 'required|in:ya,tidak',

                'kependudukan' => 'required|in:tetap,domisili',

                'foto_kk' => 'nullable|image|mimes:jpg,jpeg,png|max:20480',

            ]);


            /*
            =====================================================
            SESSION RUMAH
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
            Query ringan hanya ambil kolom diperlukan
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
            PROSES FOTO KK
            =====================================================
            */

            $foto = null;

            if ($request->hasFile('foto_kk')) {

                $file = $request->file('foto_kk');

                /*
                ---------------------------------------------
                BERSIHKAN NOMOR RUMAH
                ---------------------------------------------
                */

                $nomorRumah = str_replace('/', '-', $rumah->nomor_rumah);


                /*
                ---------------------------------------------
                NAMA FILE UNIK
                ---------------------------------------------
                Menghindari konflik upload bersamaan
                */

                $kodeUnik = now()->timestamp . '_' . mt_rand(1000, 9999);

                $namaFile = $nomorRumah . '_' . $kodeUnik . '.jpg';


                /*
                ---------------------------------------------
                FOLDER PENYIMPANAN
                ---------------------------------------------
                */

                $path = public_path('frontend/data_warga/kk');

                if (!File::exists($path)) {
                    File::makeDirectory($path, 0755, true);
                }


                /*
                ---------------------------------------------
                HAPUS FOTO LAMA KK UTAMA
                ---------------------------------------------
                */

                $lama = Keluarga::select('foto_kk')
                    ->where('rumah_id', $rumahId)
                    ->where('jenis_kk_id', 1)
                    ->first();

                if ($lama && $lama->foto_kk) {

                    $fileLama = public_path($lama->foto_kk);

                    if (File::exists($fileLama)) {
                        File::delete($fileLama);
                    }
                }



                /*
                =====================================================
                RESIZE + COMPRESS GAMBAR
                =====================================================
                */

                $manager = new ImageManager(new Driver());

                $image = $manager->read($file->getRealPath())
                    ->orient();


                /*
                Proteksi resolusi ekstrem
                */

                if ($image->width() > 5000 || $image->height() > 5000) {
                    $image = $image->scale(width: 2000);
                }


                /*
                Resize jika terlalu besar
                */

                if ($image->width() > 1024) {
                    $image = $image->scaleDown(width: 1024);
                }


                /*
                Compress gambar
                */

                $image = $image->toJpeg(65);

                $image->save($path . '/' . $namaFile);


                /*
                Path untuk database
                */

                $foto = 'frontend/data_warga/kk/' . $namaFile;
            }



            /*
            =====================================================
            SIMPAN DATABASE
            =====================================================
            */

            $existingKeluarga = Keluarga::where('rumah_id', $rumahId)
                ->where('jenis_kk_id', 1)
                ->first();


            $keluarga = Keluarga::updateOrCreate(

                [
                    'rumah_id' => $rumahId,
                    'jenis_kk_id' => 1
                ],

                [

                    'no_kk' => $request->no_kk,

                    'foto_kk' => $foto ?? optional($existingKeluarga)->foto_kk,

                    'status' => $request->status ?? 'aktif',

                    'ktp_setempat' => $request->ktp_setempat,

                    'kependudukan' => $request->kependudukan,

                    'alamat_kk' => $alamatKK,

                    'desa_kelurahan' => $desa,

                    'kecamatan' => $kecamatan,

                    'kota_kabupaten' => $kota,

                    'provinsi' => $provinsi,

                ]

            );


            /*
            =====================================================
            COMMIT DATABASE
            =====================================================
            */

            DB::commit();


            /*
            =====================================================
            REFRESH SESSION
            =====================================================
            */

            session(['rumah_id' => $rumahId]);


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
