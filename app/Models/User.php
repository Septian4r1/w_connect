<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail, JWTSubject
{
    use HasFactory, Notifiable, HasRoles;

    // =========================
    // FILLABLE
    // =========================
    protected $fillable = [
        'warga_id',
        'name',
        'email',
        'password',
    ];

    // =========================
    // HIDDEN
    // =========================
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // =========================
    // CAST
    // =========================
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // =========================
    // RELATION
    // =========================
    public function warga()
    {
        return $this->belongsTo(Warga::class, 'warga_id');
    }

    public function pengurusWilayah()
    {
        return $this->hasMany(PengurusWilayah::class, 'user_id');
    }

    public function pengurusAktif()
    {
        return $this->hasMany(PengurusWilayah::class, 'user_id')
            ->where('status', 'aktif');
    }

    // =========================
    // OPTIMIZED CACHE HELPER (ANTI N+1)
    // =========================

    public function getWilayahIds(): array
    {
        static $cache = [];

        if (isset($cache[$this->id])) {
            return $cache[$this->id];
        }

        $data = $this->pengurusWilayah()
            ->select('rw_id', 'rt_id')
            ->where(function ($q) {
                $q->whereNotNull('rw_id')
                  ->orWhereNotNull('rt_id');
            })
            ->get();

        $result = [
            'rw_ids' => $data->pluck('rw_id')->filter()->unique()->values()->toArray(),
            'rt_ids' => $data->pluck('rt_id')->filter()->unique()->values()->toArray(),
        ];

        return $cache[$this->id] = $result;
    }

    public function getRTIds()
    {
        return $this->getWilayahIds()['rt_ids'];
    }

    public function getRWIds()
    {
        return $this->getWilayahIds()['rw_ids'];
    }

    // =========================
    // ROLE WILAYAH CHECK (OPTIMIZED)
    // =========================

    public function hasRoleWilayah(string $roleName, ?int $rt_id = null, ?int $rw_id = null): bool
    {
        return $this->pengurusWilayah()
            ->where('status', 'aktif')
            ->whereHas('role', function ($q) use ($roleName) {
                $q->where('name', $roleName);
            })
            ->when($rt_id, fn ($q) => $q->where('rt_id', $rt_id))
            ->when($rw_id, fn ($q) => $q->where('rw_id', $rw_id))
            ->exists();
    }

    public function hasAnyRoleWilayah(array $roles, ?int $rt_id = null, ?int $rw_id = null): bool
    {
        return $this->pengurusWilayah()
            ->where('status', 'aktif')
            ->whereHas('role', function ($q) use ($roles) {
                $q->whereIn('name', $roles);
            })
            ->when($rt_id, fn ($q) => $q->where('rt_id', $rt_id))
            ->when($rw_id, fn ($q) => $q->where('rw_id', $rw_id))
            ->exists();
    }

    // =========================
    // JWT (SAFE + LIGHTWEIGHT)
    // =========================

    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [
            'roles' => $this->relationLoaded('roles')
                ? $this->getRoleNames()
                : $this->roles()->pluck('name'),
        ];
    }
}
