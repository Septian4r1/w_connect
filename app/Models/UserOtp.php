<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserOtp extends Model
{
    use HasFactory;

    protected $table = 'user_otps';

    protected $fillable = [
        'user_id',
        'type',
        'otp',
        'expired_at',
        'device_fingerprint',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
    ];

    public $timestamps = true;

    /**
     * Relasi ke user
     * Gunakan eager loading saat query banyak OTP agar N+1 problem tidak terjadi
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Ambil OTP aktif berdasarkan user_id
     */
    public static function getActiveOtp(sting $userId)
    {
        return self::where('user_id', $userId)
            ->where('expired_at', '>', now())
            ->first();
    }

    /**
     * Hapus OTP yang sudah expired
     * Gunakan cron job / scheduler untuk cleanup massal
     */
    public static function cleanupExpired()
    {
        DB::table('user_otps')
            ->where('expired_at', '<', now())
            ->delete();
    }
}
