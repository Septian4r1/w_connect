<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Keluarga;
use App\Models\Rumah;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class KeluargaController extends Controller
{

    /*
    =====================
    FORM CREATE
    =====================
    */

    public function create()
    {
        return view('frontend.data_warga.create_keluargas');
    }


    /*
    =====================
    SIMPAN DATA KELUARGA
    =====================
    */

    public function store(Request $request)
    {

        try {

            /*
            =====================
            VALIDASI
            =====================
            */

            $request->validate([

                'no_kk' => 'required|max:20',

                'ktp_setempat' => 'required',

                'kependudukan' => 'required',

                // Kamera besar tetap bisa upload
                'foto_kk' => 'nullable|image|mimes:jpg,jpeg,png|max:20480',

            ]);


            /*
            =====================
            SESSION RUMAH
            =====================
            */

            $rumahId = session('rumah_id');

            if (!$rumahId) {

                return back()->with([
                    'status' => 'error',
                    'message' => 'Session rumah tidak ditemukan. Silakan login ulang'
                ]);
            }


            /*
            =====================
            AMBIL RUMAH
            =====================
            */

            $rumah = Rumah::select('id', 'nomor_rumah')
                ->findOrFail($rumahId);



            /*
            =====================
            FOTO KK
            =====================
            */

            $foto = null;

            if ($request->hasFile('foto_kk')) {

                $file = $request->file('foto_kk');


                /*
                Bersihkan nomor rumah
                */

                $nomorRumah = str_replace('/', '-', $rumah->nomor_rumah);


                /*
                Nama unik
                */

                $kodeUnik = now()->format('dmY_His');

                $namaFile = $nomorRumah . '_' . $kodeUnik . '.jpg';


                /*
                Folder
                */

                $path = public_path('frontend/data_warga/kk');

                if (!File::exists($path)) {
                    File::makeDirectory($path, 0755, true);
                }


                /*
                Hapus file lama
                */

                $lama = Keluarga::select('foto_kk')
                    ->where('rumah_id', $rumahId)
                    ->first();

                if ($lama && $lama->foto_kk) {

                    $fileLama = public_path($lama->foto_kk);

                    if (File::exists($fileLama)) {
                        File::delete($fileLama);
                    }
                }


                /*
                =====================
                RESIZE + COMPRESS (Intervention v3 - OPTIMAL & SAFE)
                =====================
                */

                $manager = new ImageManager(new Driver());

                $image = $manager->read($file->getRealPath())
                    ->orient(); // auto rotate sesuai EXIF

                /*
                --------------------------------
                PROTEKSI RESOLUSI EKSTREM
                (mencegah foto 8000px+ makan RAM besar)
                --------------------------------
                */
                if ($image->width() > 5000 || $image->height() > 5000) {
                    $image = $image->scale(width: 2000);
                }

                /*
                --------------------------------
                RESIZE HANYA JIKA PERLU
                --------------------------------
                */
                if ($image->width() > 1024) {
                    $image = $image->scaleDown(width: 1024);
                }

                /*
                --------------------------------
                COMPRESS LEBIH EFISIEN
                --------------------------------
                */
                $image = $image->toJpeg(65); // 65% lebih ringan, tetap tajam

                $image->save($path . '/' . $namaFile);

                /*
                --------------------------------
                SIMPAN PATH UNTUK DATABASE
                --------------------------------
                */
                $foto = 'frontend/data_warga/kk/' . $namaFile;
            }


            /*
            =====================
            SIMPAN DATABASE
            =====================
            */
            $existingKeluarga = Keluarga::where('rumah_id', $rumahId)->first();
            Keluarga::updateOrCreate(

                ['rumah_id' => $rumahId],

                [

                    'no_kk' => $request->no_kk,

                    // Jika tidak upload jangan overwrite
                   'foto_kk' => $foto ?? optional($existingKeluarga)->foto_kk,

                    'status' => $request->status ?? 'aktif',

                    'ktp_setempat' => $request->ktp_setempat,

                    'kependudukan' => $request->kependudukan,

                    'alamat_kk' => $request->alamat_kk,

                    'desa_kelurahan' => $request->desa_kelurahan,

                    'kecamatan' => $request->kecamatan,

                    'kota_kabupaten' => $request->kota,

                    'provinsi' => $request->provinsi,

                ]

            );


            /*
            =====================
            REDIRECT
            =====================
            */

            return redirect()
                ->route('warga.create')
                ->with([
                    'status' => 'success',
                    'message' => 'Data keluarga berhasil disimpan'
                ]);
        } catch (\Exception $e) {


            return back()->with([
                'status' => 'error',
                'message' => 'Gagal menyimpan data : ' . $e->getMessage()
            ]);
        }
    }
}
