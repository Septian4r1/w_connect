<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Block extends Model
{
    use HasFactory;

    protected $table = 'blocks';

    protected $fillable = [
        'rt_id',
        'nama_blok',
        'keterangan',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    /**
     * Block milik RT
     */
    public function rt(): BelongsTo
    {
        return $this->belongsTo(Rt::class);
    }

    /**
     * Block punya banyak Rumah
     */
    public function rumahs(): HasMany
    {
        return $this->hasMany(Rumah::class);
    }

    /**
     * Optional: Block punya banyak User
     * (jika user memang ada block_id)
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'block_id');
    }

    /*
    |--------------------------------------------------------------------------
    | QUERY SCOPES (PERFORMANCE SAFE)
    |--------------------------------------------------------------------------
    */

    /**
     * Scope hanya block aktif
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Scope filter berdasarkan RT
     */
    public function scopeByRt(Builder $query, int $rtId): Builder
    {
        return $query->where('rt_id', $rtId);
    }

    /**
     * Scope eager load full area (anti N+1)
     */
    public function scopeWithFullArea(Builder $query): Builder
    {
        return $query->with([
            'rt:id,rw_id,nama_rt',
            'rt.rw:id,nama_rw'
        ]);
    }

    /**
     * Scope eager load rumah (untuk dashboard)
     */
    public function scopeWithRumahs(Builder $query): Builder
    {
        return $query->with([
            'rumahs:id,block_id,nomor_rumah,status_hunian'
        ]);
    }
}
