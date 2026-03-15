<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\Warga;

class WargaUpdateController extends Controller
{

    protected ImageManager $imageManager;

    public function __construct()
    {
        $this->imageManager = new ImageManager(new Driver());
    }

    public function updateSelfie(Request $request, $id)
    {

        $rumahId = session('rumah_id');

        if (!$rumahId) {
            abort(403);
        }

        /*
        ========================================
        DECRYPT ID
        ========================================
        */

        try {
            $wargaId = Crypt::decryptString($id);
        } catch (\Exception $e) {
            abort(404);
        }

        /*
        ========================================
        VALIDASI FILE
        ========================================
        */

        $request->validate([
            'foto_selfie' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png',
                'max:20480'
            ]
        ]);

        /*
        ========================================
        AMBIL DATA WARGA
        ========================================
        */

        $warga = Warga::select('wargas.*')
            ->join('keluargas', 'keluargas.id', '=', 'wargas.keluarga_id')
            ->where('wargas.id', $wargaId)
            ->where('keluargas.rumah_id', $rumahId)
            ->firstOrFail();

        DB::beginTransaction();

        try {

            /*
            ========================================
            PATH SAMA SEPERTI STORE
            ========================================
            */

            $basePath = public_path('frontend/data_warga/ktp');

            if (!File::exists($basePath)) {
                File::makeDirectory($basePath, 0755, true);
            }

            /*
            ========================================
            HAPUS FOTO LAMA
            ========================================
            */

            if ($warga->foto && File::exists(public_path($warga->foto))) {
                File::delete(public_path($warga->foto));
            }

            /*
            ========================================
            GENERATE NAMA FILE
            ========================================
            */

            $timestamp = now()->timestamp;
            $random = mt_rand(1000, 9999);

            $namaSlug = Str::slug($warga->nama);

            /*
            ========================================
            PROSES IMAGE
            ========================================
            */

            $fotoSelfieDb = $this->processImage(
                $request->file('foto_selfie'),
                $basePath,
                $namaSlug . '_SELFIE_' . $timestamp . '_' . $random
            );

            /*
            ========================================
            UPDATE DATABASE
            ========================================
            */

            $warga->update([
                'foto' => $fotoSelfieDb
            ]);

            DB::commit();

            return back()->with('success', 'Foto selfie berhasil diperbarui');
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', 'Upload gagal');
        }
    }

    /*
    ========================================
    HELPER PROCESS IMAGE
    ========================================
    */

    protected function processImage($file, $path, $filenameBase): string
    {

        $image = $this->imageManager
            ->read($file->getRealPath())
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
