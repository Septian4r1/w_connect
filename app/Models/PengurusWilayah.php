<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class PengurusWilayah extends Model
{
    protected $table = 'pengurus_wilayah';

    protected $fillable = [
        'user_id',
        'role_id',
        'rw_id',
        'rt_id',
        'status'
    ];

    protected $casts = [
        'rw_id' => 'integer',
        'rt_id' => 'integer',
        'user_id' => 'integer',
        'role_id' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function role()
    {
        return $this->belongsTo(\Spatie\Permission\Models\Role::class, 'role_id');
    }

    public function rw()
    {
        return $this->belongsTo(RW::class);
    }

    public function rt()
    {
        return $this->belongsTo(RT::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES (OPTIMASI QUERY)
    |--------------------------------------------------------------------------
    */

    // hanya pengurus aktif
    public function scopeActive(Builder $query)
    {
        return $query->where('status', 'aktif');
    }

    // filter berdasarkan RW
    public function scopeByRw(Builder $query, $rwId)
    {
        return $query->where('rw_id', $rwId);
    }

    // filter berdasarkan RT
    public function scopeByRt(Builder $query, $rtId)
    {
        return $query->where('rt_id', $rtId);
    }

    // cari berdasarkan role
    public function scopeByRole(Builder $query, $roleId)
    {
        return $query->where('role_id', $roleId);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER
    |--------------------------------------------------------------------------
    */

    public function isRw()
    {
        return $this->role?->name === 'rw';
    }

    public function isRt()
    {
        return $this->role?->name === 'rt';
    }

    public function isSekretaris()
    {
        return $this->role?->name === 'sekretaris';
    }

    public function isBendahara()
    {
        return $this->role?->name === 'bendahara';
    }
}
