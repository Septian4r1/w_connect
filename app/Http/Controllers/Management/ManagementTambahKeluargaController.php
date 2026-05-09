<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Keluarga;
use App\Models\Rumah;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Crypt;

class ManagementTambahKeluargaController extends Controller
{
    /*
    =====================================================
    FORM CREATE DATA KELUARGA
    =====================================================
    */
    public function ShowKeluarga(Request $request)
    {
        if (!$request->rumah_id) {
            abort(404);
        }

        try {
            // hanya untuk validasi, tidak dipakai
            Crypt::decryptString($request->rumah_id);
        } catch (\Exception $e) {
            abort(404);
        }

        return view('backend.management.warga.keluarga_management', [
            'rumah_id' => $request->rumah_id
        ]);
    }


    /*
    =====================================================
    SIMPAN DATA KELUARGA UTAMA
    =====================================================
    */
    public function store_management_Keluarga(Request $request)
    {
        DB::beginTransaction();

        try {

            // ================= VALIDASI AJAX (422 READY)
            $validator = \Validator::make($request->all(), [
                'rumah_id' => 'required',
                'no_kk' => 'required|string|max:20',
                'ktp_setempat' => 'required|in:ya,tidak',
                'kependudukan' => 'required|in:tetap,domisili',
                'foto_kk' => 'nullable|image|mimes:jpg,jpeg,png|max:20480',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            // ================= DECRYPT ID
            try {
                $rumahId = Crypt::decryptString($request->rumah_id);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'ID rumah tidak valid'
                ], 422);
            }

            // ================= CEK RUMAH
            $rumah = Rumah::select('id', 'nomor_rumah')->find($rumahId);

            if (!$rumah) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Rumah tidak ditemukan'
                ], 404);
            }

            // ================= SANITASI
            $alamatKK = strip_tags($request->alamat_kk);
            $desa = strip_tags($request->desa_kelurahan);
            $kecamatan = strip_tags($request->kecamatan);
            $kota = strip_tags($request->kota);
            $provinsi = strip_tags($request->provinsi);

            // ================= FOTO
            $foto = null;

            if ($request->hasFile('foto_kk')) {

                $file = $request->file('foto_kk');

                $nomorRumah = str_replace('/', '-', $rumah->nomor_rumah);
                $kodeUnik = now()->timestamp . '_' . mt_rand(1000, 9999);
                $namaFile = $nomorRumah . '_' . $kodeUnik . '.jpg';

                $path = public_path('frontend/data_warga/kk');

                if (!File::exists($path)) {
                    File::makeDirectory($path, 0755, true);
                }

                $manager = new ImageManager(new Driver());
                $image = $manager->read($file->getRealPath())->orient();

                if ($image->width() > 5000 || $image->height() > 5000) {
                    $image = $image->scale(width: 2000);
                }

                if ($image->width() > 1024) {
                    $image = $image->scaleDown(width: 1024);
                }

                $image = $image->toJpeg(65);
                $image->save($path . '/' . $namaFile);

                $foto = 'frontend/data_warga/kk/' . $namaFile;
            }

            // ================= UPSERT DATA
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

            DB::commit();

            // ================= SUCCESS JSON (200)
            return response()->json([
                'status' => 'success',
                'message' => 'Data keluarga berhasil disimpan',
                'redirect' => route('management.warga.tambahData_warga', [
                    'keluarga_id' => Crypt::encryptString($keluarga->id)
                ])
            ], 200);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }
}
