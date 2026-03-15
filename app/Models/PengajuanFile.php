<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanFile extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_files';

    protected $fillable = [
        'pengajuan_id',
        'nama_file',
        'path_file',
        'jenis_dokumen'
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATION
    |--------------------------------------------------------------------------
    */

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(PengajuanPerubahan::class, 'pengajuan_id');
    }
}
