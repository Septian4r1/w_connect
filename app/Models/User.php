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
    // MASS ASSIGNABLE
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
    // RELASI
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
        return $this->pengurusWilayah()->where('status', 'aktif');
    }

    // =========================
    // WILAYAH HELPER
    // =========================
    public function getWilayahIds(): array
    {
        return [
            'rw_ids' => $this->pengurusWilayah()
                ->whereNotNull('rw_id')
                ->pluck('rw_id')
                ->unique()
                ->values()
                ->toArray(),

            'rt_ids' => $this->pengurusWilayah()
                ->whereNotNull('rt_id')
                ->pluck('rt_id')
                ->unique()
                ->values()
                ->toArray(),
        ];
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
    // ROLE WILAYAH CHECK
    // =========================
    public function hasRoleWilayah(string $roleName, ?int $rt_id = null, ?int $rw_id = null): bool
    {
        return $this->pengurusWilayah()
            ->where('status', 'aktif')
            ->whereHas('role', fn($q) => $q->where('name', $roleName))
            ->when($rt_id, fn($q) => $q->where('rt_id', $rt_id))
            ->when($rw_id, fn($q) => $q->where('rw_id', $rw_id))
            ->exists();
    }

    public function hasAnyRoleWilayah(array $roles, ?int $rt_id = null, ?int $rw_id = null): bool
    {
        return $this->pengurusWilayah()
            ->where('status', 'aktif')
            ->whereHas('role', fn($q) => $q->whereIn('name', $roles))
            ->when($rt_id, fn($q) => $q->where('rt_id', $rt_id))
            ->when($rw_id, fn($q) => $q->where('rw_id', $rw_id))
            ->exists();
    }

    // =========================
    // JWT
    // =========================
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [
            'roles' => $this->getRoleNames(),
        ];
    }
}
