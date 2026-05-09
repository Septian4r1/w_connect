<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Warga;
use Illuminate\Http\Request;
use App\Models\KategoriUmur;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WargaController extends Controller
{
    /**
     * 📌 LIST DATA (OPTIMIZED FOR LARGE DATA)
     */
    public function index(Request $request)
    {
        $search = $request->search;

        /**
         * =========================================================
         * 🔥 1. BASE QUERY (JOIN - NO N+1)
         * =========================================================
         */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('showlogin_management');
        }

        $pengurus = DB::table('pengurus_wilayah')
            ->where('user_id', $user->id)
            ->where('status', 'aktif')
            ->first();



        $query = Warga::query()
            ->select([
                'wargas.id',
                'wargas.keluarga_id',
                'wargas.nama',
                'wargas.jenis_kelamin',
                'wargas.hubungan',
                'wargas.foto',
                'wargas.status',
                'wargas.tanggal_lahir',

                // 🔥 HITUNG USIA LANGSUNG DI DB (FAST)
                DB::raw("TIMESTAMPDIFF(YEAR, wargas.tanggal_lahir, CURDATE()) as usia"),

                // 🔥 RELASI (JOIN)
                'rumahs.nomor_rumah',
                'rumahs.status_hunian',
                'rumahs.status_login',

                'blocks.nama_blok',
                'rts.nama_rt',
                'rws.nama_rw',

                'keluargas.kependudukan',
                'keluargas.jenis_kk_id'
            ])

            ->leftJoin('keluargas', 'keluargas.id', '=', 'wargas.keluarga_id')
            ->leftJoin('rumahs', 'rumahs.id', '=', 'keluargas.rumah_id')
            ->leftJoin('blocks', 'blocks.id', '=', 'rumahs.block_id')
            ->leftJoin('rts', 'rts.id', '=', 'blocks.rt_id')
            ->leftJoin('rws', 'rws.id', '=', 'rts.rw_id');

        /**
         * =========================================================
         * FILTER WILAYAH LOGIN
         * =========================================================
         */

        if ($pengurus) {

            // WAJIB FILTER RW
            $query->where('rws.id', $pengurus->rw_id);

            // JIKA ADA RT -> FILTER RT
            if (!is_null($pengurus->rt_id)) {

                $query->where('rts.id', $pengurus->rt_id);
            }
        }




        /**
         * =========================================================
         * 🔍 2. SEARCH GLOBAL
         * =========================================================
         */
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('wargas.nama', 'like', "%$search%")
                    ->orWhere('wargas.jenis_kelamin', 'like', "%$search%")
                    ->orWhere('wargas.hubungan', 'like', "%$search%")
                    ->orWhere('wargas.status', 'like', "%$search%")

                    ->orWhere('keluargas.kependudukan', 'like', "%$search%")

                    ->orWhere('rumahs.nomor_rumah', 'like', "%$search%")
                    ->orWhere('rumahs.status_hunian', 'like', "%$search%")
                    ->orWhere('rumahs.status_login', 'like', "%$search%")

                    ->orWhere('blocks.nama_blok', 'like', "%$search%")
                    ->orWhere('rts.nama_rt', 'like', "%$search%")
                    ->orWhere('rws.nama_rw', 'like', "%$search%");
            });
        }


        /**
         * =========================================================
         * 🔥 3. SORTING (AMAN + FLEXIBLE)
         * =========================================================
         */

        // Ambil parameter
        $sortBy  = $request->get('sort_by', 'wargas.id');
        $sortDir = $request->get('sort_dir', 'desc');

        // 🔒 WHITELIST KOLOM
        $allowedSorts = [
            'wargas.id',
            'wargas.nama',
            'rumahs.nomor_rumah',
            'rts.nama_rt',
            'wargas.jenis_kelamin',
            'usia',
            'wargas.status',
        ];

        // Validasi kolom
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'wargas.id';
        }

        // Validasi arah
        if (!in_array($sortDir, ['asc', 'desc'])) {
            $sortDir = 'desc';
        }

        // 🔥 HANDLE KHUSUS FIELD HITUNGAN (USIA)
        if ($sortBy === 'usia') {
            $query->orderByRaw("TIMESTAMPDIFF(YEAR, wargas.tanggal_lahir, CURDATE()) $sortDir");
        } else {
            $query->orderBy($sortBy, $sortDir);
        }


        /**
         * =========================================================
         * 📄 4. PAGINATION
         * =========================================================
         */
        $wargas = $query
            ->paginate(10)
            ->withQueryString(); // 🔥 supaya search + sort tetap kebawa


        /**
         * =========================================================
         * ⚡ 5. CACHE KATEGORI UMUR
         * =========================================================
         */
        $kategoriUmur = cache()->remember('kategori_umur', 3600, function () {
            return KategoriUmur::select('nama', 'umur_min', 'umur_max')->get();
        });


        /**
         * =========================================================
         * 🔄 6. TRANSFORM DATA (RINGAN)
         * =========================================================
         */
        $wargas->getCollection()->transform(function ($warga) use ($kategoriUmur) {

            // 🔥 STATUS LOGIN KHUSUS KEPALA KELUARGA
            $isKkUtama = $warga->jenis_kk_id == 1;
            $isKepala  = $warga->hubungan === 'kepala_keluarga';

            $warga->status_login_filtered =
                ($isKepala && $isKkUtama)
                ? ($warga->status_login ?? 'offline')
                : '-';

            // 🔥 DEFAULT
            $kategoriNama = '-';

            // 🔥 HITUNG KATEGORI UMUR
            if ($warga->status === 'aktif' && $warga->usia !== null) {

                foreach ($kategoriUmur as $item) {

                    if (
                        is_null($item->umur_max)
                        ? $warga->usia >= $item->umur_min
                        : ($warga->usia >= $item->umur_min && $warga->usia <= $item->umur_max)
                    ) {
                        $kategoriNama = $item->nama;
                        break;
                    }
                }
            }

            $warga->kategori_umur_nama = $kategoriNama;

            return $warga;
        });

        $wilayahLabel = 'Semua Wilayah';

        if ($pengurus) {

            // JIKA RT
            if (!is_null($pengurus->rt_id)) {

                $rt = DB::table('rts')
                    ->where('id', $pengurus->rt_id)
                    ->value('nama_rt');

                $wilayahLabel = 'RT ' . $rt;
            }

            // JIKA RW
            else {

                $rw = DB::table('rws')
                    ->where('id', $pengurus->rw_id)
                    ->value('nama_rw');

                $wilayahLabel = 'RW ' . $rw;
            }
        }


        /**
         * =========================================================
         * 🎯 7. RETURN VIEW
         * =========================================================
         */
        return view('backend.management.warga.index', compact(
            'wargas',
            'wilayahLabel'
        ));
    }

    /**
     * 📌 DETAIL DATA (SUDAH AMAN - TIDAK BERAT)
     */
    public function show(Warga $warga)
    {
        // 🔥 LOAD RELASI SAAT DIBUTUHKAN SAJA
        $warga->load([
            'keluarga.rumah.block.rt.rw',
            'keluarga.jenisKk'
        ]);

        return response()->json([
            'foto' => $this->fotoUrl(
                $warga->foto,
                'frontend/data_warga/image/sample/User.png'
            ),

            'nik' => $warga->nik,
            'nama' => $warga->nama,
            'jenis_kelamin' => $warga->jenis_kelamin,
            'hubungan' => $warga->hubungan,
            'status_perkawinan' => $warga->status_perkawinan,
            'agama' => $warga->agama,
            'pendidikan' => $warga->pendidikan,
            'tanggal_lahir' => $warga->tanggal_lahir,
            'tempat_lahir' => $warga->tempat_lahir,
            'pekerjaan' => $warga->pekerjaan,
            'no_hp' => $warga->no_hp,
            'email' => $warga->email,
            'golongan_darah' => $warga->golongan_darah,
            'status' => $warga->status,

            'foto_ktp' => $this->fotoUrl(
                $warga->foto_ktp,
                'frontend/data_warga/image/sample/no_image.png'
            ),

            'foto_selfie' => $this->fotoUrl(
                $warga->foto,
                'frontend/data_warga/image/sample/no_image.png'
            ),

            'keluarga' => [
                'no_kk' => optional($warga->keluarga)->no_kk,
                'foto_kk' => $this->fotoUrl(
                    optional($warga->keluarga)->foto_kk,
                    'frontend/data_warga/image/sample/no_image.png'
                ),

                'jenis_kk' => optional($warga->keluarga->jenisKk)->nama,
                'alamat' => optional($warga->keluarga)->alamat_kk,
                'desa' => optional($warga->keluarga)->desa_kelurahan,
                'kecamatan' => optional($warga->keluarga)->kecamatan,
                'kota' => optional($warga->keluarga)->kota_kabupaten,
                'provinsi' => optional($warga->keluarga)->provinsi,
                'kependudukan' => optional($warga->keluarga)->kependudukan,

                'rumah' => [
                    'nomor' => optional($warga->keluarga->rumah)->nomor_rumah,
                    'blok' => optional($warga->keluarga->rumah->block)->nama_blok,
                    'rt' => optional($warga->keluarga->rumah->block->rt)->nama_rt,
                    'rw' => optional($warga->keluarga->rumah->block->rt->rw)->nama_rw,
                    'hunian' => optional($warga->keluarga->rumah)->status_hunian,
                    'login' => optional($warga->keluarga->rumah)->status_login,
                ]
            ]
        ]);
    }

    /**
     * 📌 HELPER FOTO (AMAN)
     */
    private function fotoUrl(?string $path, string $default): string
    {
        if (!empty($path) && file_exists(public_path($path))) {
            return asset($path);
        }

        return asset($default);
    }

    /**
     * 📌 TOGGLE STATUS (SUDAH OK)
     */
    public function toggleStatus(Request $request, Warga $warga)
    {
        try {
            $status = $request->status;

            if (!in_array($status, ['aktif', 'pindah', 'meninggal'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Status tidak valid'
                ]);
            }

            $warga->status = $status;
            $warga->save();

            return response()->json([
                'success' => true,
                'status' => $warga->status,
                'message' => 'Status berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
