<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Warga;
use App\Models\Rumah;
use App\Models\Keluarga;
use App\Models\KategoriUmur;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StatistikWargaController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('showlogin_management');
        }

        // =====================================================
        // PENGURUS LOGIN
        // =====================================================

        $pengurus = DB::table('pengurus_wilayah')
            ->where('user_id', $user->id)
            ->where('status', 'aktif')
            ->first();

        // =====================================================
        // KATEGORI UMUR
        // =====================================================

        $kategoriUmur = KategoriUmur::orderBy('umur_min')->get();

        // =====================================================
        // QUERY DASAR WARGA
        // =====================================================

        $wargaRWQuery = Warga::aktif();

        $rumahRWQuery = Rumah::query();

        $kkRWQuery = Keluarga::aktif();

        // =====================================================
        // FILTER BERDASARKAN WILAYAH LOGIN
        // =====================================================

        if ($pengurus) {

            // FILTER RW
            $wargaRWQuery->whereHas('keluarga.rumah.block.rt', function ($q) use ($pengurus) {
                $q->where('rw_id', $pengurus->rw_id);

                // JIKA LOGIN RT
                if (!is_null($pengurus->rt_id)) {
                    $q->where('id', $pengurus->rt_id);
                }
            });

            $rumahRWQuery->whereHas('block.rt', function ($q) use ($pengurus) {
                $q->where('rw_id', $pengurus->rw_id);

                if (!is_null($pengurus->rt_id)) {
                    $q->where('id', $pengurus->rt_id);
                }
            });

            $kkRWQuery->whereHas('rumah.block.rt', function ($q) use ($pengurus) {
                $q->where('rw_id', $pengurus->rw_id);

                if (!is_null($pengurus->rt_id)) {
                    $q->where('id', $pengurus->rt_id);
                }
            });
        }

        // =====================================================
        // DATA WARGA RW
        // =====================================================

        $totalWarga = (clone $wargaRWQuery)->count();

        $jumlahLaki = (clone $wargaRWQuery)
            ->where('jenis_kelamin', 'Laki-laki')
            ->count();

        $jumlahPerempuan = (clone $wargaRWQuery)
            ->where('jenis_kelamin', 'Perempuan')
            ->count();

        $statistikUmur = $this->getStatistikUmurQuery(
            clone $wargaRWQuery,
            $kategoriUmur
        );

        // =====================================================
        // DATA RUMAH RW
        // =====================================================

        $totalRumah = (clone $rumahRWQuery)->count();

        $totalKK = (clone $kkRWQuery)->count();

        $rasioHunian = $totalRumah > 0
            ? round(($totalKK / $totalRumah) * 100)
            : 0;

        $rumahMilik = (clone $rumahRWQuery)
            ->where('status_hunian', 'huni milik sendiri')
            ->count();

        $rumahSewa = (clone $rumahRWQuery)
            ->where('status_hunian', 'sewa')
            ->count();

        $rumahBelum = (clone $rumahRWQuery)
            ->where('status_hunian', 'belum dihuni')
            ->count();

        $rumahKosong = (clone $rumahRWQuery)
            ->where('status_hunian', 'kosong')
            ->count();

        // =====================================================
        // AMBIL LIST RT SESUAI LOGIN
        // =====================================================

        $rtQuery = DB::table('rts');

        if ($pengurus) {

            $rtQuery->where('rw_id', $pengurus->rw_id);

            // jika login RT
            if (!is_null($pengurus->rt_id)) {
                $rtQuery->where('id', $pengurus->rt_id);
            }
        }

        $rts = $rtQuery
            ->orderBy('nama_rt')
            ->get();

        // =====================================================
        // DATA DINAMIS PER RT
        // =====================================================

        $listRT = [];

        $listRumahRT = [];

        foreach ($rts as $rt) {

            $kodeRT = str_pad($rt->nama_rt, 3, '0', STR_PAD_LEFT);

            $listRT[$kodeRT] = $this->statistikRT(
                $rt->id,
                $kategoriUmur
            );

            $listRumahRT[$kodeRT] = $this->statistikRumahRT(
                $rt->id
            );
        }

        return view(
            'backend.management.dashboard.index_statistik',
            compact(
                'totalWarga',
                'jumlahLaki',
                'jumlahPerempuan',
                'statistikUmur',

                'totalRumah',
                'totalKK',
                'rasioHunian',
                'rumahMilik',
                'rumahSewa',
                'rumahBelum',
                'rumahKosong',

                'listRT',
                'listRumahRT'
            )
        );
    }

    private function statistikRT(int $rtId, $kategoriUmur)
    {
        // ======================
        // QUERY RT
        // ======================

        $wargaQuery = Warga::aktif()
            ->whereHas('keluarga.rumah.block', function ($q) use ($rtId) {

                $q->where('rt_id', $rtId);
            });

        $totalWarga = (clone $wargaQuery)->count();

        $jumlahLaki = (clone $wargaQuery)
            ->where('jenis_kelamin', 'Laki-laki')
            ->count();

        $jumlahPerempuan = (clone $wargaQuery)
            ->where('jenis_kelamin', 'Perempuan')
            ->count();

        $statistikUmur = $this->getStatistikUmurQuery(
            $wargaQuery,
            $kategoriUmur
        );

        return compact(
            'totalWarga',
            'jumlahLaki',
            'jumlahPerempuan',
            'statistikUmur'
        );
    }

    private function getStatistikUmurQuery($query, $kategoriUmur)
    {
        // ======================
        // SELECT RAW UMUR
        // ======================

        $selects = [];

        foreach ($kategoriUmur as $kategori) {

            $name = $kategori->nama;

            if ($kategori->umur_max === null) {

                $selects[] = "
                    SUM(
                        CASE
                            WHEN TIMESTAMPDIFF(
                                YEAR,
                                tanggal_lahir,
                                CURDATE()
                            ) >= {$kategori->umur_min}
                            THEN 1
                            ELSE 0
                        END
                    ) AS `{$name}`
                ";
            } else {

                $selects[] = "
                    SUM(
                        CASE
                            WHEN TIMESTAMPDIFF(
                                YEAR,
                                tanggal_lahir,
                                CURDATE()
                            )
                            BETWEEN {$kategori->umur_min}
                            AND {$kategori->umur_max}
                            THEN 1
                            ELSE 0
                        END
                    ) AS `{$name}`
                ";
            }
        }

        $raw = implode(', ', $selects);

        $umurCounts = (clone $query)
            ->select(DB::raw($raw))
            ->first();

        // ======================
        // ICON & WARNA ASLI
        // ======================

        $icons = [
            [
                'icon' => 'bi-emoji-smile',
                'color' => 'success'
            ],
            [
                'icon' => 'bi-emoji-laughing',
                'color' => 'info'
            ],
            [
                'icon' => 'bi-person',
                'color' => 'primary'
            ],
            [
                'icon' => 'bi-person-badge',
                'color' => 'warning'
            ],
            [
                'icon' => 'bi-briefcase',
                'color' => 'dark'
            ],
            [
                'icon' => 'bi-person-workspace',
                'color' => 'secondary'
            ],
            [
                'icon' => 'bi-person-lines-fill',
                'color' => 'danger'
            ],
            [
                'icon' => 'bi-people-fill',
                'color' => 'purple'
            ]
        ];

        $statistikUmur = [];

        foreach ($kategoriUmur as $i => $kategori) {

            $statistikUmur[] = [

                'nama' => $kategori->nama,

                'jumlah' => $umurCounts->{$kategori->nama} ?? 0,

                'icon' => $icons[$i]['icon'] ?? 'bi-people-fill',

                'color' => $icons[$i]['color'] ?? 'secondary',
            ];
        }

        return $statistikUmur;
    }

    private function statistikRumahRT(int $rtId)
    {
        // ======================
        // QUERY RUMAH RT
        // ======================

        $rumahQuery = Rumah::query()
            ->whereHas('block', function ($q) use ($rtId) {

                $q->where('rt_id', $rtId);
            });

        $totalRumah = (clone $rumahQuery)->count();

        // ======================
        // TOTAL KK
        // ======================

        $totalKK = Keluarga::aktif()
            ->whereHas('rumah.block', function ($q) use ($rtId) {

                $q->where('rt_id', $rtId);
            })
            ->count();

        // ======================
        // RASIO HUNIAN
        // ======================

        $rasioHunian = $totalRumah > 0
            ? round(($totalKK / $totalRumah) * 100)
            : 0;

        // ======================
        // STATUS HUNIAN
        // ======================

        $rumahMilik = (clone $rumahQuery)
            ->where('status_hunian', 'huni milik sendiri')
            ->count();

        $rumahSewa = (clone $rumahQuery)
            ->where('status_hunian', 'sewa')
            ->count();

        $rumahBelum = (clone $rumahQuery)
            ->where('status_hunian', 'belum dihuni')
            ->count();

        $rumahKosong = (clone $rumahQuery)
            ->where('status_hunian', 'kosong')
            ->count();

        return compact(
            'totalRumah',
            'totalKK',
            'rasioHunian',
            'rumahMilik',
            'rumahSewa',
            'rumahBelum',
            'rumahKosong'
        );
    }
}
