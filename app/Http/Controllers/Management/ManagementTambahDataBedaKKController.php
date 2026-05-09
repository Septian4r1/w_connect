<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use App\Models\JenisKk;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Warga;
use App\Models\Keluarga;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ManagementTambahDataBedaKKController extends Controller
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

        $keluargaIdEncrypted = Crypt::encryptString($warga->keluarga->id);

        // ✅ encrypt warga id untuk form submit
        $wargaIdEncrypted = Crypt::encryptString($warga->id);

        $jenisKk = JenisKk::where('id', '!=', 1)->get();

        return view(
            'backend.management.warga.tambah_dataKeluarga_bedaKK',
            compact(
                'warga',
                'keluargaIdEncrypted',
                'wargaIdEncrypted',
                'jenisKk'
            )
        );
    }

    // =========================
    // STORE KK BEDA (BACKEND)
    // ========================= 
    public function DataWargaBedakkStore(Request $request)
    {
        DB::beginTransaction();

        try {

            /*
        =====================================================
        VALIDASI
        =====================================================
        */
            $validated = $request->validate([
                'warga_id' => 'required|string',
                'no_kk' => 'required|string|max:20|unique:keluargas,no_kk',
                'jenis_kk_id' => 'required|exists:jenis_kks,id',
                'ktp_setempat' => 'required',
                'kependudukan' => 'required',
                'foto_kk' => 'nullable|image|mimes:jpg,jpeg,png|max:20480',

                'alamat_kk' => 'nullable|string|max:255',
                'desa_kelurahan' => 'nullable|string|max:100',
                'kecamatan' => 'nullable|string|max:100',
                'kota' => 'nullable|string|max:100',
                'provinsi' => 'nullable|string|max:100',
            ]);

            /*
        =====================================================
        DECRYPT WARGA ID
        =====================================================
        */
            try {
                $wargaId = Crypt::decryptString($validated['warga_id']);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'ID tidak valid / manipulasi terdeteksi'
                ], 400);
            }


            /*
        =====================================================
        AMBIL DATA WARGA & RUMAH
        =====================================================
        */
            $warga = Warga::with(['keluarga.rumah:id,nomor_rumah'])
                ->select('id', 'keluarga_id')
                ->findOrFail($wargaId);

            if (!$warga->keluarga || !$warga->keluarga->rumah) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data keluarga atau rumah tidak ditemukan'
                ], 404);
            }

            $rumah = $warga->keluarga->rumah;
            // dd([
            //     'warga_id' => $warga->id,
            //     'keluarga_id' => $warga->keluarga->id ?? null,
            //     'rumah' => $rumah,
            //     'nomor_rumah' => $rumah->nomor_rumah ?? null
            // ]);


            /*
        =====================================================
        SANITASI
        =====================================================
        */
            $sanitize = fn($v) => $v ? strip_tags(trim($v)) : null;


            /*
        =====================================================
        UPLOAD FOTO
        =====================================================
        */
            $foto = null;

            if ($request->hasFile('foto_kk')) {

                $file = $request->file('foto_kk');

                if (!$file->isValid()) {
                    throw new \Exception('File tidak valid');
                }

                $nomorRumah = str_replace('/', '-', $rumah->nomor_rumah);
                $namaFile = $nomorRumah . '_' . time() . '.jpg';

                $path = public_path('backend/data_warga/kk');

                if (!File::exists($path)) {
                    File::makeDirectory($path, 0755, true);
                }

                $image = $this->imageManager
                    ->read($file->getRealPath())
                    ->orient();

                if ($image->width() > 1024) {
                    $image = $image->scaleDown(width: 1024);
                }

                $image->toJpeg(65)->save($path . '/' . $namaFile);

                $foto = 'backend/data_warga/kk/' . $namaFile;
            }


            /*
        =====================================================
        SIMPAN
        =====================================================
        */
            $keluarga = Keluarga::create([
                'rumah_id' => $rumah->id,
                'jenis_kk_id' => $validated['jenis_kk_id'],
                'no_kk' => $validated['no_kk'],
                'foto_kk' => $foto,
                'status' => 'aktif',
                'ktp_setempat' => $validated['ktp_setempat'],
                'kependudukan' => $validated['kependudukan'],

                'alamat_kk' => $sanitize($validated['alamat_kk']),
                'desa_kelurahan' => $sanitize($validated['desa_kelurahan']),
                'kecamatan' => $sanitize($validated['kecamatan']),
                'kota_kabupaten' => $sanitize($validated['kota']),
                'provinsi' => $sanitize($validated['provinsi']),
            ]);


            DB::commit();


            /*
        =====================================================
        SUCCESS RESPONSE
        =====================================================
        */
            return response()->json([
                'status' => true,
                'message' => 'KK baru berhasil dibuat',
                'redirect' => route('management.warga.tambahKeluargaBedaKK', [
                    'id' => Crypt::encryptString($keluarga->id)
                ])
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json([
                'status' => false,
                'message' => $e->validator->errors()->first()
            ], 422);
        } catch (\Throwable $e) {

            DB::rollBack();


            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }
}
