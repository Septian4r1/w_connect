<?php

namespace App\Http\Controllers\Management\RumahDanKK;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

use App\Models\Rumah;
use App\Models\Keluarga;
use App\Models\Warga;
use App\Models\KategoriUmur;

class ManagementRumahDanKK extends Controller
{
    public function index(Request $request)
    {
        /*
        |----------------------------------------------------------------------
        | AUTH USER
        |----------------------------------------------------------------------
        */

        $user = Auth::user();

        if (!$user) {
            return redirect()->route('showlogin_management');
        }

        /*
        |----------------------------------------------------------------------
        | DATA PENGURUS WILAYAH
        |----------------------------------------------------------------------
        */

        $pengurus = DB::table('pengurus_wilayah')
            ->where('user_id', $user->id)
            ->where('status', 'aktif')
            ->first();

        /*
        |----------------------------------------------------------------------
        | SEARCH
        |----------------------------------------------------------------------
        */

        $search = trim($request->search);

        /*
        |----------------------------------------------------------------------
        | SORTING
        |----------------------------------------------------------------------
        */

        $allowedSort = [
            'rumahs.nomor_rumah',
            'blocks.nama_blok',
            'rts.nama_rt',
            'rumahs.status_hunian',
            'total_kk',
            'total_warga',
        ];

        $sortBy = in_array($request->sort_by, $allowedSort)
            ? $request->sort_by
            : 'rumahs.id';

        $sortDir = $request->sort_dir === 'asc'
            ? 'asc'
            : 'desc';

        /*
        |----------------------------------------------------------------------
        | BASE QUERY
        |----------------------------------------------------------------------
        */

        $query = Rumah::query()

            ->leftJoin('blocks', 'blocks.id', '=', 'rumahs.block_id')
            ->leftJoin('rts', 'rts.id', '=', 'blocks.rt_id')
            ->leftJoin('rws', 'rws.id', '=', 'rts.rw_id')

            /*
            |------------------------------------------------------------------
            | TOTAL KK & WARGA
            |------------------------------------------------------------------
            */

            ->withCount([
                'keluargas as total_kk' => function ($q) {
                    $q->where('status', 'aktif');
                },

                'wargas as total_warga',
            ])

            /*
            |------------------------------------------------------------------
            | SELECT
            |------------------------------------------------------------------
            */

            ->addSelect([

                'rumahs.*',

                'blocks.nama_blok',

                'rts.nama_rt',

                'rws.nama_rw',

                /*
                |--------------------------------------------------------------
                | KEPALA KELUARGA
                |--------------------------------------------------------------
                */

                'kepala_keluarga' => Warga::select('wargas.nama')

                    ->join(
                        'keluargas',
                        'keluargas.id',
                        '=',
                        'wargas.keluarga_id'
                    )

                    ->whereColumn(
                        'keluargas.rumah_id',
                        'rumahs.id'
                    )

                    ->where(
                        'wargas.hubungan',
                        'kepala_keluarga'
                    )

                    ->limit(1)
            ]);

        /*
        |----------------------------------------------------------------------
        | FILTER WILAYAH LOGIN
        |----------------------------------------------------------------------
        */

        if ($pengurus) {

            /*
            |------------------------------------------------------------------
            | WAJIB FILTER RW
            |------------------------------------------------------------------
            */

            $query->where('rws.id', $pengurus->rw_id);

            /*
            |------------------------------------------------------------------
            | JIKA RT LOGIN -> FILTER RT
            |------------------------------------------------------------------
            */

            if (!is_null($pengurus->rt_id)) {

                $query->where('rts.id', $pengurus->rt_id);
            }
        }

        /*
        |----------------------------------------------------------------------
        | SEARCH
        |----------------------------------------------------------------------
        */

        $query->when($search, function ($query) use ($search) {

            $query->where(function ($q) use ($search) {

                $q->where(
                    'rumahs.nomor_rumah',
                    'like',
                    "%{$search}%"
                )

                    ->orWhere(
                        'rumahs.status_hunian',
                        'like',
                        "%{$search}%"
                    )

                    ->orWhere(
                        'blocks.nama_blok',
                        'like',
                        "%{$search}%"
                    )

                    ->orWhere(
                        'rts.nama_rt',
                        'like',
                        "%{$search}%"
                    )

                    ->orWhere(
                        'rws.nama_rw',
                        'like',
                        "%{$search}%"
                    )

                    /*
                    |----------------------------------------------------------
                    | SEARCH FORMAT RT
                    |----------------------------------------------------------
                    */

                    ->orWhereRaw("
                        CONCAT('RT ', rts.nama_rt) LIKE ?
                    ", ["%{$search}%"])

                    /*
                    |----------------------------------------------------------
                    | SEARCH FORMAT RW
                    |----------------------------------------------------------
                    */

                    ->orWhereRaw("
                        CONCAT('RW ', rws.nama_rw) LIKE ?
                    ", ["%{$search}%"])

                    /*
                    |----------------------------------------------------------
                    | SEARCH KETERANGAN
                    |----------------------------------------------------------
                    */

                    ->orWhereRaw("
                        CASE

                            WHEN rumahs.status_hunian = 'kosong'
                            THEN 'Rumah Tanggung Jawab BTN'

                            WHEN rumahs.status_hunian IN (
                                'huni milik sendiri',
                                'sewa',
                                'belum huni'
                            )
                            AND (
                                (
                                    SELECT COUNT(*)
                                    FROM keluargas
                                    WHERE keluargas.rumah_id = rumahs.id
                                    AND keluargas.status = 'aktif'
                                ) = 0

                                OR

                                (
                                    SELECT COUNT(*)
                                    FROM wargas
                                    INNER JOIN keluargas
                                        ON keluargas.id = wargas.keluarga_id
                                    WHERE keluargas.rumah_id = rumahs.id
                                ) = 0
                            )

                            THEN 'Wajib Update Data KK dan Warga'

                            ELSE 'Data Lengkap'

                        END LIKE ?
                    ", ["%{$search}%"])

                    /*
                    |----------------------------------------------------------
                    | SEARCH NAMA KEPALA KELUARGA
                    |----------------------------------------------------------
                    */

                    ->orWhereExists(function ($sub) use ($search) {

                        $sub->select(DB::raw(1))
                            ->from('wargas')

                            ->join(
                                'keluargas',
                                'keluargas.id',
                                '=',
                                'wargas.keluarga_id'
                            )

                            ->whereColumn(
                                'keluargas.rumah_id',
                                'rumahs.id'
                            )

                            ->where(
                                'wargas.hubungan',
                                'kepala_keluarga'
                            )

                            ->where(
                                'wargas.nama',
                                'like',
                                "%{$search}%"
                            );
                    });
            });
        });

        /*
        |----------------------------------------------------------------------
        | PAGINATION DATA
        |----------------------------------------------------------------------
        */

        $rumahs = $query

            ->orderBy($sortBy, $sortDir)

            ->paginate(10)

            ->appends([
                'search'   => $search,
                'sort_by'  => $sortBy,
                'sort_dir' => $sortDir,
            ]);

        /*
        |----------------------------------------------------------------------
        | STATISTIK BERDASARKAN WILAYAH LOGIN
        |----------------------------------------------------------------------
        */

        $statQueryRumah = Rumah::query()
            ->leftJoin('blocks', 'blocks.id', '=', 'rumahs.block_id')
            ->leftJoin('rts', 'rts.id', '=', 'blocks.rt_id')
            ->leftJoin('rws', 'rws.id', '=', 'rts.rw_id');

        $statQueryKK = Keluarga::query()
            ->leftJoin('rumahs', 'rumahs.id', '=', 'keluargas.rumah_id')
            ->leftJoin('blocks', 'blocks.id', '=', 'rumahs.block_id')
            ->leftJoin('rts', 'rts.id', '=', 'blocks.rt_id')
            ->leftJoin('rws', 'rws.id', '=', 'rts.rw_id');

        $statQueryWarga = Warga::query()
            ->leftJoin('keluargas', 'keluargas.id', '=', 'wargas.keluarga_id')
            ->leftJoin('rumahs', 'rumahs.id', '=', 'keluargas.rumah_id')
            ->leftJoin('blocks', 'blocks.id', '=', 'rumahs.block_id')
            ->leftJoin('rts', 'rts.id', '=', 'blocks.rt_id')
            ->leftJoin('rws', 'rws.id', '=', 'rts.rw_id');

        /*
        |----------------------------------------------------------------------
        | FILTER STATISTIK BERDASARKAN WILAYAH
        |----------------------------------------------------------------------
        */

        if ($pengurus) {

            $statQueryRumah->where('rws.id', $pengurus->rw_id);
            $statQueryKK->where('rws.id', $pengurus->rw_id);
            $statQueryWarga->where('rws.id', $pengurus->rw_id);

            if (!is_null($pengurus->rt_id)) {

                $statQueryRumah->where('rts.id', $pengurus->rt_id);
                $statQueryKK->where('rts.id', $pengurus->rt_id);
                $statQueryWarga->where('rts.id', $pengurus->rt_id);
            }
        }

        /*
        |----------------------------------------------------------------------
        | TOTAL
        |----------------------------------------------------------------------
        */

        $totalRumah = (clone $statQueryRumah)->count();

        $totalKK = (clone $statQueryKK)->count();

        $totalWarga = (clone $statQueryWarga)->count();

        /*
        |----------------------------------------------------------------------
        | STATUS HUNIAN
        |----------------------------------------------------------------------
        */

        $statusHunian = (clone $statQueryRumah)

            ->select(
                'rumahs.status_hunian',
                DB::raw('COUNT(*) as total')
            )

            ->groupBy('rumahs.status_hunian')

            ->pluck('total', 'status_hunian');

        $totalSewa =
            $statusHunian['sewa'] ?? 0;

        $totalMilikSendiri =
            $statusHunian['huni milik sendiri'] ?? 0;

        /*
        |----------------------------------------------------------------------
        | RUMAH KOSONG
        |----------------------------------------------------------------------
        */

        $totalKosong = (clone $statQueryRumah)
            ->where('rumahs.status_hunian', 'kosong')
            ->count();

        /*
        |----------------------------------------------------------------------
        | BELUM DIHUNI
        |----------------------------------------------------------------------
        */

        $totalBelumDihuni = (clone $statQueryRumah)
            ->where('rumahs.status_hunian', 'belum huni')
            ->count();

        /*
        |----------------------------------------------------------------------
        | JENIS KELAMIN
        |----------------------------------------------------------------------
        */

        $genderStats = (clone $statQueryWarga)

            ->select(
                'wargas.jenis_kelamin',
                DB::raw('COUNT(*) as total')
            )

            ->groupBy('wargas.jenis_kelamin')

            ->pluck('total', 'jenis_kelamin');

        $totalLaki =
            $genderStats['Laki-laki'] ?? 0;

        $totalPerempuan =
            $genderStats['Perempuan'] ?? 0;

        /*
        |----------------------------------------------------------------------
        | KATEGORI UMUR
        |----------------------------------------------------------------------
        */

        $kategoriUmur = KategoriUmur::orderBy('umur_min')->get();

        $statistikUmur = $kategoriUmur->map(function ($kategori) use ($pengurus) {

            $query = Warga::query()

                ->leftJoin(
                    'keluargas',
                    'keluargas.id',
                    '=',
                    'wargas.keluarga_id'
                )

                ->leftJoin(
                    'rumahs',
                    'rumahs.id',
                    '=',
                    'keluargas.rumah_id'
                )

                ->leftJoin(
                    'blocks',
                    'blocks.id',
                    '=',
                    'rumahs.block_id'
                )

                ->leftJoin(
                    'rts',
                    'rts.id',
                    '=',
                    'blocks.rt_id'
                )

                ->leftJoin(
                    'rws',
                    'rws.id',
                    '=',
                    'rts.rw_id'
                );

            /*
            |--------------------------------------------------------------
            | FILTER WILAYAH
            |--------------------------------------------------------------
            */

            if ($pengurus) {

                $query->where('rws.id', $pengurus->rw_id);

                if (!is_null($pengurus->rt_id)) {

                    $query->where('rts.id', $pengurus->rt_id);
                }
            }

            /*
            |--------------------------------------------------------------
            | FILTER UMUR
            |--------------------------------------------------------------
            */

            if ($kategori->umur_max !== null) {

                $query->whereRaw("
                    TIMESTAMPDIFF(
                        YEAR,
                        wargas.tanggal_lahir,
                        CURDATE()
                    ) BETWEEN ? AND ?
                ", [
                    $kategori->umur_min,
                    $kategori->umur_max
                ]);
            } else {

                $query->whereRaw("
                    TIMESTAMPDIFF(
                        YEAR,
                        wargas.tanggal_lahir,
                        CURDATE()
                    ) >= ?
                ", [
                    $kategori->umur_min
                ]);
            }

            return [
                'nama'       => $kategori->nama,
                'jumlah'     => $query->count(),
                'keterangan' => $kategori->keterangan,
            ];
        });

        /*
        |----------------------------------------------------------------------
        | LABEL WILAYAH
        |----------------------------------------------------------------------
        */

        $wilayahLabel = 'Semua Wilayah';

        if ($pengurus) {

            /*
            |------------------------------------------------------------------
            | JIKA LOGIN RT
            |------------------------------------------------------------------
            */

            if (!is_null($pengurus->rt_id)) {

                $rt = DB::table('rts')
                    ->where('id', $pengurus->rt_id)
                    ->value('nama_rt');

                $wilayahLabel = 'RT ' . $rt;
            }

            /*
            |------------------------------------------------------------------
            | JIKA LOGIN RW
            |------------------------------------------------------------------
            */ else {

                $rw = DB::table('rws')
                    ->where('id', $pengurus->rw_id)
                    ->value('nama_rw');

                $wilayahLabel = 'RW ' . $rw;
            }
        }

        /*
        |----------------------------------------------------------------------
        | RETURN VIEW
        |----------------------------------------------------------------------
        */

        return view(
            'backend.management.rumah_dan_kk.index',
            compact(
                'rumahs',
                'wilayahLabel',

                'totalRumah',
                'totalKK',
                'totalWarga',

                'totalSewa',
                'totalMilikSendiri',

                'totalKosong',
                'totalBelumDihuni',

                'totalLaki',
                'totalPerempuan',

                'statistikUmur'
            )
        );
    }



    /*
|--------------------------------------------------------------------------
| DELETE RUMAH
|--------------------------------------------------------------------------
*/

    public function destroy(string $id)
    {
        DB::beginTransaction();

        try {

            /*
        |------------------------------------------------------------------
        | AMBIL DATA RUMAH + RELASI
        |------------------------------------------------------------------
        */

            $rumah = Rumah::with([
                'keluargas.wargas'
            ])->findOrFail($id);

            $nomorRumah = $rumah->nomor_rumah;

            /*
        |------------------------------------------------------------------
        | LOOP KELUARGA
        |------------------------------------------------------------------
        */

            foreach ($rumah->keluargas as $keluarga) {

                /*
            |--------------------------------------------------------------
            | LOOP WARGA
            |--------------------------------------------------------------
            */

                foreach ($keluarga->wargas as $warga) {

                    /*
                |----------------------------------------------------------
                | HAPUS FOTO KTP
                |----------------------------------------------------------
                */

                    if (
                        !empty($warga->foto_ktp) &&
                        file_exists(public_path($warga->foto_ktp))
                    ) {

                        unlink(public_path($warga->foto_ktp));
                    }

                    /*
                |----------------------------------------------------------
                | HAPUS FOTO SELFIE
                |----------------------------------------------------------
                */

                    if (
                        !empty($warga->foto) &&
                        file_exists(public_path($warga->foto))
                    ) {

                        unlink(public_path($warga->foto));
                    }

                    /*
                |----------------------------------------------------------
                | HAPUS DATA WARGA
                |----------------------------------------------------------
                */

                    $warga->delete();
                }

                /*
            |--------------------------------------------------------------
            | HAPUS FOTO KK
            |--------------------------------------------------------------
            */

                if (
                    !empty($keluarga->foto_kk) &&
                    file_exists(public_path($keluarga->foto_kk))
                ) {

                    unlink(public_path($keluarga->foto_kk));
                }

                /*
            |--------------------------------------------------------------
            | HAPUS DATA KELUARGA
            |--------------------------------------------------------------
            */

                $keluarga->delete();
            }

            /*
        |------------------------------------------------------------------
        | HAPUS RUMAH
        |------------------------------------------------------------------
        */

            $rumah->delete();

            DB::commit();

            /*
        |------------------------------------------------------------------
        | SUCCESS
        |------------------------------------------------------------------
        */

            return redirect()
                ->back()
                ->with(
                    'success',
                    "Data rumah {$nomorRumah} beserta seluruh KK dan warga berhasil dihapus"
                );
        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error('Gagal hapus rumah', [
                'error' => $e->getMessage(),
                'id'    => $id
            ]);

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Terjadi kesalahan saat menghapus data rumah'
                );
        }
    }

    /*
|--------------------------------------------------------------------------
| UPDATE RUMAH
|--------------------------------------------------------------------------
*/

    public function update(Request $request, string $id)
    {
        try {

            $request->validate([
                'nomor_rumah'  => 'required|max:50',
                'status_hunian' => 'required'
            ]);

            $rumah = Rumah::findOrFail($id);

            $rumah->update([
                'nomor_rumah'  => $request->nomor_rumah,
                'status_hunian' => $request->status_hunian,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data rumah berhasil diperbarui'
            ]);
        } catch (\Throwable $e) {

            Log::error('Gagal update rumah', [
                'error' => $e->getMessage(),
                'id'    => $id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data rumah'
            ], 500);
        }
    }
}
