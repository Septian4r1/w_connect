<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserToken extends Model
{
    use HasFactory;

    protected $table = 'user_tokens';

    protected $fillable = [
        'user_id',
        'token',
        'refresh_token',
        'device',
        'ip_address',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public $timestamps = true;

    /**
     * Relasi ke user
     * Gunakan eager loading saat query banyak token untuk menghindari N+1 problem
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Ambil token aktif berdasarkan user_id
     */
    public static function getActiveToken($userId)
    {
        return self::where('user_id', $userId)
            ->where('expires_at', '>', now())
            ->first();
    }

    /**
     * Hapus token yang sudah expired
     * Gunakan cron job / scheduler untuk cleanup massal
     */
    public static function cleanupExpired()
    {
        DB::table('user_tokens')
            ->where('expires_at', '<', now())
            ->delete();
    }

    /**
     * Hapus semua token user tertentu (misal logout dari semua device)
     */
    public static function revokeAllTokens($userId)
    {
        self::where('user_id', $userId)->delete();
    }

    /**
     * Hapus token tertentu berdasarkan refresh_token
     */
    public static function revokeByRefreshToken($refreshToken)
    {
        self::where('refresh_token', $refreshToken)->delete();
    }
}
