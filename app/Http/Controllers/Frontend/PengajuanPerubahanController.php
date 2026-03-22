<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use App\Services\NomorPengajuanService;
use App\Models\Warga;
use App\Models\PengajuanPerubahan;
use App\Models\PengajuanFile;
use App\Models\PengajuanApproval;
use Intervention\Image\ImageManager;
use Intervention\Image\Facades\Image;



class PengajuanPerubahanController extends Controller
{
    public function store(Request $request)
    {
        try {
            $rumahId = session('rumah_id');
            if (!$rumahId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Session rumah hilang / tidak ditemukan'
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
                'perihal'   => ['required', 'string', 'max:50', 'in:' . implode(',', $fieldPerubahan)],
                'data_awal' => ['nullable', 'string', 'max:255'],
                'data_baru' => ['nullable', 'string', 'max:255'],
                'alasan'    => ['nullable', 'string', 'max:500'],
                'dokumen'   => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
                'foto_ktp'  => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:5120']
            ]);

            try {
                $wargaId = Crypt::decryptString($validated['id_warga']);
            } catch (\Throwable $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'ID warga tidak valid / gagal decrypt',
                    'error' => $e->getMessage()
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

                // Upload file
                $this->handleFileUpload($request, $pengajuan, 'dokumen', 'dokumen_pendukung');
                $this->handleFileUpload($request, $pengajuan, 'foto_ktp', 'foto_ktp');

                // Approval pertama
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
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    private function handleFileUpload(Request $request, PengajuanPerubahan $pengajuan, string $inputName, string $jenisDokumen)
    {
        // Jika tidak ada file, langsung return
        if (!$request->hasFile($inputName)) {
            return;
        }

        $file = $request->file($inputName);

        // Jika file tidak valid, log dan return
        if (!$file->isValid()) {
            Log::warning("Upload file '$inputName' gagal: " . $file->getErrorMessage());
            return;
        }

        // Buat kode unik & nama file dasar
        $kodeUnik = now()->timestamp . '_' . mt_rand(1000, 9999);
        $extension = strtolower($file->extension());
        $namaFile = $pengajuan->no_pengajuan . '_' . $kodeUnik;
        $pathFolder = public_path('frontend/data_warga/file_pengajuan');

        // Buat folder jika belum ada
        if (!File::exists($pathFolder)) {
            File::makeDirectory($pathFolder, 0755, true);
        }

        try {
            // Jika gambar, proses dengan Intervention Image
            if (in_array($extension, ['jpg', 'jpeg', 'png'])) {
                $image = Image::make($file->getRealPath())->orientate();

                // Resize jika lebar lebih dari 1024px
                if ($image->width() > 1024) {
                    $image->resize(1024, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                }

                $namaFile .= '.jpg';
                $image->save($pathFolder . '/' . $namaFile, 65); // kualitas 65%
            } else {
                // File selain gambar (PDF, docx, dll)
                $namaFile .= '.' . $extension;
                $file->move($pathFolder, $namaFile);
            }

            $dbPath = 'frontend/data_warga/file_pengajuan/' . $namaFile;

            // Simpan info file ke database
            PengajuanFile::create([
                'pengajuan_id'  => $pengajuan->id,
                'nama_file'     => $file->getClientOriginalName(),
                'path_file'     => $dbPath,
                'jenis_dokumen' => $jenisDokumen
            ]);
        } catch (\Exception $e) {
            Log::error("Gagal memproses file '$inputName': " . $e->getMessage());
        }
    }
}
