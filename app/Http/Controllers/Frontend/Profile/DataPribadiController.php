<?php

namespace App\Http\Controllers\Frontend\Profile;

use App\Http\Controllers\Controller;
use App\Models\Rumah;
use Illuminate\Http\Request;

class DataPribadiController extends Controller
{
    /**
     * Menampilkan data pribadi rumah dan anggota keluarga
     */
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | 1. Ambil session rumah
        |--------------------------------------------------------------------------
        | Pastikan user sudah login dan memiliki session 'rumah_id'.
        */
        $rumahId = session('rumah_id');

        if (!$rumahId) {
            return redirect()->route('showlogin')
                ->with('error', 'Session login hilang');
        }

        /*
        |--------------------------------------------------------------------------
        | 2. Query Rumah dengan relasi keluarga dan wargas
        |--------------------------------------------------------------------------
        | Kita ingin menampilkan data rumah, keluarga, kepala keluarga,
        | serta semua anggota keluarga secara efisien (ANTI N+1).
        */
        $rumah = Rumah::query()
            ->select([
                'rumahs.id',
                'rumahs.block_id',
                'rumahs.nomor_rumah',
                'rumahs.alamat_lengkap',
                'rumahs.desa',
                'rumahs.kelurahan',
                'rumahs.kode_pos',
                'rumahs.status_login',
                'rumahs.status_hunian'
            ])
            ->with([
                /*
                |------------------------------------------------------------------
                | Relasi Keluarga
                |------------------------------------------------------------------
                | Memuat data keluarga dan nested relasi anggota keluarga (wargas)
                */
                'keluarga' => function ($q) {
                    $q->select([
                        'keluargas.id',
                        'keluargas.rumah_id',
                        'keluargas.no_kk',
                        'keluargas.foto_kk',
                        'keluargas.status',
                        'keluargas.ktp_setempat',
                        'keluargas.kependudukan',
                        'keluargas.alamat_kk',
                        'keluargas.desa_kelurahan',
                        'keluargas.kecamatan',
                        'keluargas.kota_kabupaten',
                        'keluargas.provinsi'
                    ])
                        ->with(['wargas' => function ($q) {
                            $q->select([
                                'wargas.id',
                                'wargas.keluarga_id',
                                'wargas.nama',
                                'wargas.nik',
                                'wargas.hubungan',
                                'wargas.jenis_kelamin',
                                'wargas.status_perkawinan',
                                'wargas.agama',
                                'wargas.pendidikan',
                                'wargas.tanggal_lahir',
                                'wargas.tempat_lahir',
                                'wargas.pekerjaan',
                                'wargas.no_hp',
                                'wargas.email',
                                'wargas.status'
                            ])
                                ->where('wargas.status', 'aktif'); // hanya anggota aktif
                        }]);
                },

                /*
                |------------------------------------------------------------------
                | Kepala Keluarga
                |------------------------------------------------------------------
                | Memuat data kepala keluarga (hasOneThrough), prefix wajib wargas.*
                */
                'kepalaKeluarga' => function ($q) {
                    $q->select([
                        'wargas.id',
                        'wargas.keluarga_id',
                        'wargas.nik',
                        'wargas.nama',
                        'wargas.jenis_kelamin',
                        'wargas.hubungan',
                        'wargas.status_perkawinan',
                        'wargas.agama',
                        'wargas.pendidikan',
                        'wargas.tanggal_lahir',
                        'wargas.tempat_lahir',
                        'wargas.province',
                        'wargas.pekerjaan',
                        'wargas.no_hp',
                        'wargas.email',
                        'wargas.golongan_darah',
                        'wargas.foto_ktp',
                        'wargas.foto',
                        'wargas.status'
                    ]);
                }
            ])
            ->find($rumahId); // Ambil data berdasarkan session rumah_id

        /*
        |--------------------------------------------------------------------------
        | 3. Validasi
        |--------------------------------------------------------------------------
        | Jika rumah tidak ditemukan, tampilkan error 404
        */
        if (!$rumah) {
            abort(404, 'Data rumah tidak ditemukan');
        }

        /*
        |--------------------------------------------------------------------------
        | 4. Return view
        |--------------------------------------------------------------------------
        | Kirim data rumah, keluarga, kepala keluarga, dan anggota keluarga
        | ke view blade 'data_pribadi.blade.php'
        */
        return view('frontend.data_warga.data_pribadi.data_pribadi', compact('rumah'));
    }
}
