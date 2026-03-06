<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail, JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Hidden attributes
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * ======================================================
     * JWT IDENTIFIER
     * ======================================================
     * Return primary key user yang akan disimpan
     * di dalam payload JWT
     */
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    /**
     * ======================================================
     * CUSTOM JWT CLAIMS
     * ======================================================
     * Data tambahan yang dimasukkan ke payload JWT
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }
}
