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
        'organization_id', // FIX: hapus spasi
        'nama_blok',
        'keterangan',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
        'organization_id' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    /**
     * Block milik RT lama
     */
    public function rt(): BelongsTo
    {
        return $this->belongsTo(Rt::class);
    }

    /**
     * Block milik organization RT baru
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(
            Organization::class,
            'organization_id'
        );
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
     * Scope filter berdasarkan RT lama
     */
    public function scopeByRt(
        Builder $query,
        int $rtId
    ): Builder {
        return $query->where('rt_id', $rtId);
    }

    /**
     * Scope filter berdasarkan organization
     */
    public function scopeByOrganization(
        Builder $query,
        int $organizationId
    ): Builder {
        return $query->where(
            'organization_id',
            $organizationId
        );
    }

    /**
     * Scope eager load full area
     */
    public function scopeWithFullArea(
        Builder $query
    ): Builder {
        return $query->with([
            'rt:id,rw_id,nama_rt',
            'rt.rw:id,nama_rw',
            'organization:id,type,code,name',
        ]);
    }

    /**
     * Scope eager load rumah
     */
    public function scopeWithRumahs(
        Builder $query
    ): Builder {
        return $query->with([
            'rumahs:id,block_id,nomor_rumah,status_hunian'
        ]);
    }
}
