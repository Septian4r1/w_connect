<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengurusWilayah extends Model
{
    protected $table = 'pengurus_wilayah';

    protected $fillable = [
        'user_id',
        'role_id',
        'organization_id',
        'rw_id',
        'rt_id',
        'status',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'user_id'         => 'integer',
        'role_id'         => 'integer',
        'organization_id' => 'integer',
        'rw_id'           => 'integer',
        'rt_id'           => 'integer',
        'start_date'      => 'date',
        'end_date'        => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(
            \Spatie\Permission\Models\Role::class,
            'role_id'
        );
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function rw(): BelongsTo
    {
        return $this->belongsTo(Rw::class);
    }

    public function rt(): BelongsTo
    {
        return $this->belongsTo(Rt::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    // hanya data aktif
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'aktif')
            ->whereNull('end_date');
    }

    // histori user
    public function scopeHistoryByUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId)
            ->orderByDesc('start_date');
    }

    // masih menjabat
    public function scopeCurrentlyActive(Builder $query): Builder
    {
        return $query->where('status', 'aktif')
            ->whereNull('end_date');
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function isActive(): bool
    {
        return $this->status === 'aktif' && is_null($this->end_date);
    }

    public function isExpired(): bool
    {
        return !is_null($this->end_date);
    }

    public function duration(): ?int
    {
        if (!$this->start_date || !$this->end_date) {
            return null;
        }

        return $this->start_date->diffInDays($this->end_date);
    }

    /*
    |--------------------------------------------------------------------------
    | ROLE HELPERS (tetap kamu pertahankan)
    |--------------------------------------------------------------------------
    */

    public function isRw(): bool
    {
        return str_contains(strtolower($this->role?->name ?? ''), 'rw');
    }

    public function isRt(): bool
    {
        return str_contains(strtolower($this->role?->name ?? ''), 'rt');
    }

    public function isSekretaris(): bool
    {
        return str_contains(strtolower($this->role?->name ?? ''), 'sekretaris');
    }

    public function isBendahara(): bool
    {
        return str_contains(strtolower($this->role?->name ?? ''), 'bendahara');
    }

    public function isSuperAdmin(): bool
    {
        return $this->role?->name === 'super_admin';
    }

    public function getStartDateFormatAttribute()
    {
        return $this->start_date
            ? \Carbon\Carbon::parse($this->start_date)->format('d-m-Y')
            : '-';
    }

    public function getEndDateFormatAttribute()
    {
        return $this->end_date
            ? \Carbon\Carbon::parse($this->end_date)->format('d-m-Y')
            : 'Masih Menjabat';
    }
}
