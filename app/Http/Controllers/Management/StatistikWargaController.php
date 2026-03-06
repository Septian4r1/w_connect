<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Warga;
use App\Models\Rumah;
use App\Models\Keluarga;
use App\Models\KategoriUmur;
use Illuminate\Support\Facades\DB;

class StatistikWargaController extends Controller
{
    public function index()
    {
        // ======================
        // DATA WARGA RW
        // ======================
        $totalWarga = Warga::aktif()->count();

        $jumlahLaki = Warga::aktif()->where('jenis_kelamin', 'Laki-laki')->count();
        $jumlahPerempuan = Warga::aktif()->where('jenis_kelamin', 'Perempuan')->count();

        // ======================
        // STATISTIK UMUR RW
        // ======================
        $kategoriUmur = KategoriUmur::orderBy('umur_min')->get();

        $statistikUmur = $this->getStatistikUmurQuery(Warga::query(), $kategoriUmur);

        // ======================
        // DATA PERUMAHAN
        // ======================
        $totalRumah = Rumah::count();
        $totalKK = Keluarga::aktif()->count();
        $rasioHunian = $totalRumah > 0 ? round(($totalKK / $totalRumah) * 100) : 0;

        $rumahMilik = Rumah::where('status_hunian', 'huni milik sendiri')->count();
        $rumahSewa = Rumah::where('status_hunian', 'sewa')->count();
        $rumahBelum = Rumah::where('status_hunian', 'belum huni')->count();
        $rumahKosong = Rumah::where('status_hunian', 'kosong')->count();

        // ======================
        // DATA PER RT
        // ======================
        $rt1 = $this->statistikRT(1, $kategoriUmur);
        $rt2 = $this->statistikRT(2, $kategoriUmur);

        return view('backend.management.dashboard.index_statistik', compact(
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
            'rt1',
            'rt2'
        ));
    }

    private function statistikRT(int $rtId, $kategoriUmur)
    {
        // Base query untuk RT tertentu
        $wargaQuery = Warga::aktif()
            ->whereHas('keluarga.rumah.block', fn($q) => $q->where('rt_id', $rtId));

        $totalWarga = (clone $wargaQuery)->count();
        $jumlahLaki = (clone $wargaQuery)->where('jenis_kelamin', 'Laki-laki')->count();
        $jumlahPerempuan = (clone $wargaQuery)->where('jenis_kelamin', 'Perempuan')->count();

        $statistikUmur = $this->getStatistikUmurQuery($wargaQuery, $kategoriUmur);

        return compact('totalWarga', 'jumlahLaki', 'jumlahPerempuan', 'statistikUmur');
    }

    private function getStatistikUmurQuery($query, $kategoriUmur)
    {
        if ($query instanceof Warga) {
            $query = Warga::query()->whereKey($query->pluck('id')); // clone
        }

        // Bangun select raw CASE WHEN untuk umur
        $selects = [];
        foreach ($kategoriUmur as $kategori) {
            $name = $kategori->nama;
            if ($kategori->umur_max === null) {
                $selects[] = "SUM(CASE WHEN TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) >= {$kategori->umur_min} THEN 1 ELSE 0 END) AS `{$name}`";
            } else {
                $selects[] = "SUM(CASE WHEN TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) BETWEEN {$kategori->umur_min} AND {$kategori->umur_max} THEN 1 ELSE 0 END) AS `{$name}`";
            }
        }

        $raw = implode(', ', $selects);
        $umurCounts = (clone $query)->select(DB::raw($raw))->first();

        // Ikat dengan icon & warna
        $icons = [
            'bi-emoji-smile' => 'success',
            'bi-emoji-laughing' => 'info',
            'bi-person' => 'primary',
            'bi-person-badge' => 'warning',
            'bi-briefcase' => 'dark',
            'bi-person-workspace' => 'secondary',
            'bi-person-lines-fill' => 'danger',
            'bi-people-fill' => 'purple'
        ];

        $statistikUmur = [];
        $i = 0;
        foreach ($kategoriUmur as $kategori) {
            $icon = array_keys($icons)[$i] ?? 'bi-people';
            $color = $icons[$icon] ?? 'secondary';
            $statistikUmur[] = [
                'nama' => $kategori->nama,
                'jumlah' => $umurCounts->{$kategori->nama} ?? 0,
                'icon' => $icon,
                'color' => $color
            ];
            $i++;
        }

        return $statistikUmur;
    }
}
