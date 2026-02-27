<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;


class Keluarga extends Model
{
    use HasFactory;

    protected $table = 'keluargas';

    /*
    |--------------------------------------------------------------------------
    | MASS ASSIGNMENT
    |--------------------------------------------------------------------------
    | Kolom yang boleh diisi melalui form
    */

    protected $fillable = [

        'rumah_id',
        'no_kk',
        'foto_kk',

        'status',
        'ktp_setempat',
        'kependudukan',

        'alamat_kk',

        'desa_kelurahan',
        'kecamatan',
        'kota_kabupaten',
        'provinsi'

    ];


    /*
    |--------------------------------------------------------------------------
    | DEFAULT VALUE
    |--------------------------------------------------------------------------
    | Nilai default otomatis
    */

    protected $attributes = [

        'status' => 'aktif',
        'ktp_setempat' => 'ya',
        'kependudukan' => 'tetap'

    ];


    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    // Keluarga milik Rumah
    public function rumah()
    {
        return $this->belongsTo(Rumah::class);
    }



    // Keluarga punya banyak Warga
    public function wargas(): HasMany
    {
        return $this->hasMany(Warga::class, 'keluarga_id');
    }


    // Ambil Kepala Keluarga langsung
    public function kepalaKeluarga(): HasOne
    {
        return $this->hasOne(Warga::class, 'keluarga_id')
            ->where('hubungan', 'kepala_keluarga');
    }

    // Relasi anggota keluarga
    public function anggota()
    {
        return $this->hasMany(Warga::class, 'keluarga_id');
    }


    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */


    // Scope keluarga aktif
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }


    // Scope berdasarkan rumah
    public function scopeByRumah($query, $rumahId)
    {
        return $query->where('rumah_id', $rumahId);
    }



    /*
    |--------------------------------------------------------------------------
    | ACCESSOR (HELPER DATA)
    |--------------------------------------------------------------------------
    */


    // Ambil alamat lengkap otomatis
    public function getAlamatLengkapAttribute()
    {
        return $this->alamat_kk . ', ' .
            $this->desa_kelurahan . ', ' .
            $this->kecamatan . ', ' .
            $this->kota_kabupaten . ', ' .
            $this->provinsi;
    }
}
