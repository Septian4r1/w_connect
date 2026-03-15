<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisKk extends Model
{
    protected $table = 'jenis_kks';

    protected $fillable = [
        'nama',
        'keterangan'
    ];

    /*
    ======================================
    RELASI KE KELUARGA
    ======================================
    1 jenis kk bisa dipakai banyak keluarga
    */

    public function keluargas()
    {
        return $this->hasMany(Keluarga::class, 'jenis_kk_id');
    }
}
