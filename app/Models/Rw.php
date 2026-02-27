<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Builder;

class Rw extends Model
{
    protected $fillable = [
        'nama_rw',
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

    public function rts(): HasMany
    {
        return $this->hasMany(Rt::class);
    }

    public function blocks(): HasManyThrough
    {
        return $this->hasManyThrough(Block::class, Rt::class);
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

    public function scopeWithFullStructure(Builder $query): Builder
    {
        return $query->with([
            'rts.blocks'
        ]);
    }
}
