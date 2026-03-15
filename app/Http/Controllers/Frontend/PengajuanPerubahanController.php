<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use App\Services\NomorPengajuanService;
use Illuminate\Support\Str;
use App\Models\Warga;
use App\Models\PengajuanPerubahan;
use App\Models\PengajuanFile;
use App\Models\PengajuanApproval;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class PengajuanPerubahanController extends Controller
{

    public function store(Request $request)
    {
        try {

            $rumahId = session('rumah_id');
            if (!$rumahId) abort(403);

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
                'perihal'   => ['required', 'string', 'max:50', 'in:' . implode(',', $fieldPerubahan)],
                'data_awal' => ['nullable', 'string', 'max:255'],
                'data_baru' => ['nullable', 'string', 'max:255'],
                'alasan'    => ['nullable', 'string', 'max:500'],
                'dokumen'   => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120']
            ]);

            try {
                $wargaId = Crypt::decryptString($validated['id_warga']);
            } catch (\Throwable $e) {
                abort(404);
            }

            $warga = Warga::query()
                ->select('wargas.id', 'wargas.nama')
                ->join('keluargas', 'keluargas.id', '=', 'wargas.keluarga_id')
                ->where('wargas.id', $wargaId)
                ->where('keluargas.rumah_id', $rumahId)
                ->firstOrFail();

            return DB::transaction(function () use ($request, $validated, $warga, $wargaId) {

                $noPengajuan = NomorPengajuanService::generate('PGJ', 'CSR', 'RW_016');

                $pengajuan = PengajuanPerubahan::create([
                    'no_pengajuan'    => $noPengajuan,
                    'warga_id'        => $wargaId,
                    'nama_pengaju'    => $warga->nama,
                    'jenis_pengajuan' => 'perubahan_data',
                    'field_perubahan' => strip_tags($validated['perihal']),
                    'data_awal'       => strip_tags($validated['data_awal'] ?? ''),
                    'data_baru'       => strip_tags($validated['data_baru'] ?? ''),
                    'alasan'          => strip_tags($validated['alasan'] ?? ''),
                    'status'          => 'pending',
                    'created_by'      => $wargaId
                ]);

                /*
                ============================================
                UPLOAD FILE SAMA SEPERTI FOTO KK
                ============================================
                */

                if ($request->hasFile('dokumen')) {

                    $file = $request->file('dokumen');

                    $manager = new ImageManager(new Driver());

                    $kodeUnik = now()->timestamp . '_' . mt_rand(1000, 9999);
                    $namaFile = $noPengajuan . '_' . $kodeUnik . '.jpg';

                    $pathFolder = public_path('frontend/data_warga/file_pengajuan');

                    if (!File::exists($pathFolder)) {
                        File::makeDirectory($pathFolder, 0755, true);
                    }

                    if (in_array($file->extension(), ['jpg', 'jpeg', 'png'])) {

                        $image = $manager->read($file->getRealPath())->orient();

                        if ($image->width() > 5000 || $image->height() > 5000) {
                            $image = $image->scale(width: 2000);
                        }

                        if ($image->width() > 1024) {
                            $image = $image->scaleDown(width: 1024);
                        }

                        $image = $image->toJpeg(65);
                        $image->save($pathFolder . '/' . $namaFile);
                    } else {

                        $namaFile = $noPengajuan . '_' . $kodeUnik . '.' . $file->extension();
                        $file->move($pathFolder, $namaFile);
                    }

                    $dbPath = 'frontend/data_warga/file_pengajuan/' . $namaFile;

                    PengajuanFile::create([
                        'pengajuan_id'  => $pengajuan->id,
                        'nama_file'     => $file->getClientOriginalName(),
                        'path_file'     => $dbPath,
                        'jenis_dokumen' => 'dokumen_pendukung'
                    ]);
                }

                /*
                ============================================
                APPROVAL LEVEL PERTAMA
                ============================================
                */

                PengajuanApproval::create([
                    'pengajuan_id' => $pengajuan->id,
                    'level'        => 'admin',
                    'status'       => 'pending'
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
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem'
            ], 500);
        }
    }
}
