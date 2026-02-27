<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // <- diganti
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use App\Models\Keluarga;
use App\Models\Warga;

class Rumah extends Authenticatable
{
    use HasFactory;

    protected $table = 'rumahs';

    protected $fillable = [
        'block_id',
        'nomor_rumah',
        'alamat_lengkap',
        'desa',
        'kelurahan',
        'kode_pos',
        'password',
        'status_login',
        'status_hunian',
        'layanan_approval',
        'remember_token',
    ];

    protected $casts = [
        'status_hunian' => 'string',
        'status_login' => 'string',
    ];

    // Mutator untuk hash password otomatis
    public function setPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['password'] = Hash::make($value);
        }
    }

    /* RELATIONS */
    public function block(): BelongsTo
    {
        return $this->belongsTo(Block::class);
    }

    public function keluarga(): HasOne
    {
        return $this->hasOne(Keluarga::class);
    }

    public function kepalaKeluarga(): HasOneThrough
    {
        return $this->hasOneThrough(
            Warga::class,     // model tujuan
            Keluarga::class,  // model perantara
            'rumah_id',       // FK di tabel keluarga
            'keluarga_id',    // FK di tabel warga
            'id',             // PK di rumah
            'id'              // PK di keluarga
        )->where('hubungan', 'kepala_keluarga');
    }

    public function scopeWithFullArea(Builder $query): Builder
    {
        return $query->with(['block.rt.rw']);
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('status_hunian', 'kosong');
    }

    public function scopeOnline(Builder $query): Builder
    {
        return $query->where('status_login', 'online');
    }

    public function scopeOccupied(Builder $query): Builder
    {
        return $query->whereIn('status_hunian', ['huni, milik sendiri', 'sewa', 'belum huni']);
    }
}
