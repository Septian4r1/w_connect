<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Rumah;

class ProfileController extends Controller
{
    public function index()
    {

        /*
        |--------------------------------------------------------------------------
        | 1. Ambil Session Rumah
        |--------------------------------------------------------------------------
        */

        $rumahId = session('rumah_id');

        if (!$rumahId) {
            return redirect()->route('showlogin')
                ->with('error', 'Session login hilang');
        }


        /*
        |--------------------------------------------------------------------------
        | 2. Query SUPER OPTIMIZED (ANTI N+1)
        |--------------------------------------------------------------------------
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
                |--------------------------------------------------------------------------
                | DATA KELUARGA
                |--------------------------------------------------------------------------
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
                    ]);
                },

                /*
                |--------------------------------------------------------------------------
                | KEPALA KELUARGA (HAS ONE THROUGH)
                |--------------------------------------------------------------------------
                | WAJIB prefix wargas.*
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
                },


                /*
                |--------------------------------------------------------------------------
                | ANGGOTA KELUARGA
                |--------------------------------------------------------------------------
                */

                'keluarga.wargas' => function ($q) {

                    $q->select([
                        'wargas.id',
                        'wargas.keluarga_id',
                        'wargas.nama',
                        'wargas.nik',
                        'wargas.hubungan',
                        'wargas.foto',
                        'wargas.status'
                    ])
                        ->where('wargas.status', 'aktif');
                }

            ])

            ->find($rumahId);



        /*
        |--------------------------------------------------------------------------
        | 3. Validasi
        |--------------------------------------------------------------------------
        */

        if (!$rumah) {
            abort(404, 'Data rumah tidak ditemukan');
        }


        /*
        |--------------------------------------------------------------------------
        | 4. Return View
        |--------------------------------------------------------------------------
        */

        return view('frontend.profil', compact('rumah'));
    }
}
