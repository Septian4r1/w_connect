<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

use App\Notifications\PengajuanBerhasilNotification;

use App\Services\NomorPengajuanService;
use App\Models\Rumah;
use App\Models\Warga;
use App\Models\PengajuanPerubahan;
use App\Models\PengajuanFile;
use App\Models\PengajuanApproval;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class PengajuanPerubahanController extends Controller
{
    protected ImageManager $imageManager;

    public function __construct()
    {
        $this->imageManager = new ImageManager(new Driver());
    }

    /*
    =====================================================
    STORE PENGAJUAN PERUBAHAN
    =====================================================
    */
    /*
=====================================================
STORE PENGAJUAN PERUBAHAN
=====================================================
*/
    public function store(Request $request)
    {
        try {

            $rumahId = session('rumah_id');

            if (!$rumahId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Session rumah tidak ditemukan'
                ], 403);
            }

            $fieldPerubahan = [
                'nama',
                'jenis_kelamin',
                'hubungan',
                'status_perkawinan',
                'agama',
                'pendidikan',
                'tanggal_lahir',
                'tempat_lahir',
                'pekerjaan',
                'no_hp',
                'golongan_darah',
                'foto_ktp'
            ];

            $validated = $request->validate([
                'id_warga'  => ['required', 'string'],
                'perihal'   => ['required', 'string', 'in:' . implode(',', $fieldPerubahan)],
                'data_awal' => ['nullable', 'string', 'max:255'],
                'data_baru' => ['nullable', 'string', 'max:255'],
                'alasan'    => ['nullable', 'string', 'max:500'],

                'dokumen'   => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
                'foto_ktp'  => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:5120'],
            ]);

            try {
                $wargaId = Crypt::decryptString($validated['id_warga']);
            } catch (\Throwable $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'ID warga tidak valid'
                ], 404);
            }

            $warga = Warga::query()
                ->select('wargas.id', 'wargas.nama')
                ->join('keluargas', 'keluargas.id', '=', 'wargas.keluarga_id')
                ->where('wargas.id', $wargaId)
                ->where('keluargas.rumah_id', $rumahId)
                ->firstOrFail();

            return DB::transaction(function () use ($request, $validated, $warga, $wargaId) {

                $noPengajuan = NomorPengajuanService::generate('PGJ', 'CSR', 'RW_016');

                $dataAwal = $validated['data_awal'] ?? '';
                $dataBaru = $validated['data_baru'] ?? '';

                /*
            =====================================================
            KHUSUS PERUBAHAN FOTO KTP
            =====================================================
            Ambil path KTP lama dari database warga
            */
                if ($validated['perihal'] === 'foto_ktp') {
                    $wargaFull = Warga::find($wargaId);
                    $dataAwal = $wargaFull->foto_ktp ?? '';
                }

                $pengajuan = PengajuanPerubahan::create([
                    'no_pengajuan'    => $noPengajuan,
                    'warga_id'        => $wargaId,
                    'nama_pengaju'    => $warga->nama,
                    'jenis_pengajuan' => 'perubahan_data',
                    'field_perubahan' => strip_tags($validated['perihal']),
                    'data_awal'       => $dataAwal,
                    'data_baru'       => $dataBaru,
                    'alasan'          => strip_tags($validated['alasan'] ?? ''),
                    'status'          => 'pending',
                    'created_by'      => $wargaId
                ]);

                /*
            ============================
            UPLOAD DOKUMEN
            ============================
            */
                $this->handleFileUpload($request, $pengajuan, 'dokumen', 'dokumen_pendukung');

                /*
            =====================================================
            UPLOAD FOTO KTP BARU
            =====================================================
            */
                $pathFotoBaru = $this->handleFileUpload(
                    $request,
                    $pengajuan,
                    'foto_ktp',
                    'foto_ktp'
                );

                /*
            =====================================================
            SIMPAN KE DATA_BARU
            =====================================================
            */
                if ($validated['perihal'] === 'foto_ktp' && $pathFotoBaru) {
                    $pengajuan->update([
                        'data_baru' => $pathFotoBaru
                    ]);
                }

                $rumah = Rumah::find(session('rumah_id'));

                if ($rumah) {
                    $rumah->notify(new PengajuanBerhasilNotification([
                        'pengajuan_id' => $pengajuan->id,
                        'no_pengajuan' => $noPengajuan,
                        'perihal' => $validated['perihal'] ?? 'Pengajuan',
                        'nama' => $warga->nama
                    ]));
                }

                PengajuanApproval::create([
                    'pengajuan_id' => $pengajuan->id,
                    'level' => 'admin',
                    'status' => 'pending'
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Pengajuan berhasil dikirim',
                    'no_pengajuan' => $noPengajuan
                ]);
            });
        } catch (\Throwable $e) {

            Log::error('Error Pengajuan Perubahan', [
                'message' => $e->getMessage(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan server'
            ], 500);
        }
    }

    /*
    =====================================================
    HANDLE UPLOAD FILE
    =====================================================
    */
    private function handleFileUpload(Request $request, PengajuanPerubahan $pengajuan, string $inputName, string $jenisDokumen)
    {
        if (!$request->hasFile($inputName)) {
            return null;
        }

        $file = $request->file($inputName);

        if (!$file->isValid()) {
            return null;
        }

        $basePath = public_path('frontend/data_warga/file_pengajuan');

        if (!File::exists($basePath)) {
            File::makeDirectory($basePath, 0755, true);
        }



        $name = $pengajuan->warga->nama ?? 'unknown';
        $timestamp = now()->timestamp;
        $random = mt_rand(1000, 9999);
        $filenameBase = $pengajuan->no_pengajuan . '_' . $name . '_' . $timestamp . '_' . $random;

        $extension = strtolower(
            $file->guessExtension()
                ?? $file->extension()
                ?? pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION)
        );

        try {

            if (in_array($extension, ['jpg', 'jpeg', 'png'])) {

                $pathDb = $this->processImage(
                    $file,
                    $basePath,
                    $filenameBase
                );
            } else {

                $filename = $filenameBase . '.' . $extension;
                $file->move($basePath, $filename);

                $pathDb = 'frontend/data_warga/file_pengajuan/' . $filename;
            }

            PengajuanFile::create([
                'pengajuan_id'  => $pengajuan->id,
                'nama_file'     => $file->getClientOriginalName(),
                'path_file'     => $pathDb,
                'jenis_dokumen' => $jenisDokumen
            ]);

            return $pathDb; // 🔥 ini yang penting

        } catch (\Throwable $e) {

            Log::error('Fallback upload file', [
                'error' => $e->getMessage()
            ]);

            $filename = $filenameBase . '.' . $extension;
            $file->move($basePath, $filename);

            $pathDb = 'frontend/data_warga/file_pengajuan/' . $filename;

            PengajuanFile::create([
                'pengajuan_id'  => $pengajuan->id,
                'nama_file'     => $file->getClientOriginalName(),
                'path_file'     => $pathDb,
                'jenis_dokumen' => $jenisDokumen
            ]);

            return $pathDb;
        }
    }

    /*
    =====================================================
    PROCESS IMAGE (VERSI STABIL PRODUCTION)
    =====================================================
    */
    protected function processImage($file, $path, $filenameBase): string
    {
        $image = $this->imageManager
            ->read($file->getRealPath())
            ->orient();

        /*
        -----------------------------------------------------
        PROTEKSI RESOLUSI SANGAT BESAR (kamera HP 48MP)
        -----------------------------------------------------
        */
        if ($image->width() > 8000 || $image->height() > 8000) {
            $image = $image->scaleDown(width: 3000);
        }

        /*
        -----------------------------------------------------
        RESIZE NORMAL PRODUCTION
        -----------------------------------------------------
        */
        if ($image->width() > 2000) {
            $image = $image->scaleDown(width: 2000);
        }

        /*
        -----------------------------------------------------
        COMPRESS IMAGE
        -----------------------------------------------------
        */
        $image = $image->toJpeg(85);

        $filename = $filenameBase . '.jpg';

        /*
        -----------------------------------------------------
        SAVE FILE
        -----------------------------------------------------
        */
        $image->save($path . '/' . $filename);

        return 'frontend/data_warga/file_pengajuan/' . $filename;
    }
}
