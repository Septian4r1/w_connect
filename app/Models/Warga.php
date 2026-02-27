<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    /*
    RELATION
    */

    public function keluarga()
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

    public function getUmurAttribute()
    {
        return $this->tanggal_lahir?->age;
    }
}
