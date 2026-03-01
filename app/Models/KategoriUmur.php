<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriUmur extends Model
{
    protected $table = 'kategori_umur'; // nama tabel

    protected $fillable = [
        'nama',
        'umur_min',
        'umur_max',
        'keterangan',
    ];

    // Timestamps
    public $timestamps = true;
}
