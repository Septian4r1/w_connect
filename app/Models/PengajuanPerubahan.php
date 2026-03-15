<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PengajuanPerubahan extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_perubahan';

    protected $casts = [
        'data_awal' => 'array',
        'data_baru' => 'array'
    ];

    protected $fillable = [
        'no_pengajuan',
        'warga_id',
        'nama_pengaju',
        'jenis_pengajuan',
        'field_perubahan',
        'data_awal',
        'data_baru',
        'alasan',
        'status',
        'created_by'
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATION
    |--------------------------------------------------------------------------
    */

    // pengajuan milik warga
    public function warga(): BelongsTo
    {
        return $this->belongsTo(Warga::class);
    }

    // user pembuat pengajuan
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // approval berjenjang
    public function approvals(): HasMany
    {
        return $this->hasMany(PengajuanApproval::class, 'pengajuan_id');
    }

    // file lampiran
    public function files(): HasMany
    {
        return $this->hasMany(PengajuanFile::class, 'pengajuan_id');
    }
}
