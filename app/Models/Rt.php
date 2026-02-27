<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Rt extends Model
{
    protected $fillable = [
        'rw_id',
        'nama_rt',
        'ketua_user_id',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function rw(): BelongsTo
    {
        return $this->belongsTo(Rw::class);
    }

    public function blocks(): HasMany
    {
        return $this->hasMany(Block::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'aktif');
    }

    public function scopeWithFullArea(Builder $query): Builder
    {
        return $query->with([
            'rw:id,nama_rw'
        ]);
    }
}
