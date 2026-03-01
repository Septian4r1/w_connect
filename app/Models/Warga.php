<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;
use App\Models\KategoriUmur;

class Warga extends Model
{
    use HasFactory;

    protected $table = 'wargas';

    protected $fillable = [
        'keluarga_id',
        'nik',
        'nama',
        'jenis_kelamin',
        'hubungan',
        'status_perkawinan',
        'agama',
        'pendidikan',
        'tanggal_lahir',
        'tempat_lahir',
        'province',
        'pekerjaan',
        'no_hp',
        'email',
        'golongan_darah',
        'foto_ktp',
        'foto',
        'status',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    // =========================
    // Auto append fields untuk JSON / Blade
    // =========================
    protected $appends = [
        'umur',
        'kategori_umur'
    ];

    /*
    RELATION
    */
    public function keluarga(): BelongsTo
    {
        return $this->belongsTo(Keluarga::class);
    }

    /*
    SCOPES
    */
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeHubungan($query, $hubungan)
    {
        return $query->where('hubungan', $hubungan);
    }

    public function scopeByKeluarga($query, $id)
    {
        return $query->where('keluarga_id', $id);
    }

    /*
    HELPER
    */

    // =========================
    // Hitung umur lengkap: Tahun Bulan Hari
    // =========================
    public function getUmurAttribute(): string
    {
        // Hanya hitung umur jika status 'aktif'
        if ($this->status !== 'aktif' || !$this->tanggal_lahir) {
            return '-';
        }

        $lahir = Carbon::parse($this->tanggal_lahir);
        $diff = $lahir->diff(Carbon::now());

        return $diff->y . ' Tahun ' . $diff->m . ' Bulan ' . $diff->d . ' Hari';
    }

    // =========================
    // Format tanggal lahir: 02 March 2026
    // =========================
    public function getTanggalLahirFormattedAttribute(): string
    {
        return $this->tanggal_lahir
            ? $this->tanggal_lahir->translatedFormat('d F Y')
            : '-';
    }

    // =========================
    // Kategori umur otomatis
    // Mengembalikan array:
    // [
    //     'nama' => 'Dewasa Muda',
    //     'keterangan' => 'Dewasa awal, usia produktif'
    // ]
    // =========================
    public function getKategoriUmurAttribute(): array|string
    {
        if (!$this->tanggal_lahir) {
            return '-';
        }

        $umur = Carbon::parse($this->tanggal_lahir)->age;

        $kategori = KategoriUmur::where('umur_min', '<=', $umur)
            ->where(function ($q) use ($umur) {
                $q->where('umur_max', '>=', $umur)
                    ->orWhereNull('umur_max');
            })
            ->first();

        if (!$kategori) {
            return '-';
        }

        return [
            'nama' => $kategori->nama,
            'keterangan' => $kategori->keterangan,
        ];
    }
}
