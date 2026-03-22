<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

class LoginRateLimiter
{
    protected static $limit = 3;
    protected static $minutes = 15;

    public static function tooManyAttempts($ip, $nomorRumah = null)
    {
        $data = self::getData($ip, $nomorRumah);

        if (!$data) {
            return false;
        }

        return $data['attempts'] >= self::$limit;
    }

    public static function hit($ip, $nomorRumah = null)
    {
        $key = self::key($ip, $nomorRumah);
        $data = self::getData($ip, $nomorRumah);

        $attempts = $data['attempts'] ?? 0;
        $expiresAt = now()->addMinutes(self::$minutes);

        Cache::put($key, [
            'attempts' => $attempts + 1,
            'expires_at' => $expiresAt
        ], $expiresAt);
    }

    public static function reset($ip, $nomorRumah = null)
    {
        Cache::forget(self::key($ip, $nomorRumah));
    }

    public static function remainingMinutes($ip, $nomorRumah = null)
    {
        $data = self::getData($ip, $nomorRumah);

        if (!$data || !isset($data['expires_at'])) {
            return 0;
        }

        $seconds = now()->diffInSeconds($data['expires_at'], false);

        if ($seconds <= 0) {
            return 0;
        }

        return ceil($seconds / 60);
    }

    protected static function getData($ip, $nomorRumah)
    {
        $data = Cache::get(self::key($ip, $nomorRumah));

        // jika format lama (int)
        if (is_int($data)) {
            return [
                'attempts' => $data,
                'expires_at' => now()->addMinutes(self::$minutes)
            ];
        }

        return $data;
    }

    protected static function key($ip, $nomorRumah)
    {
        return 'login_rate:' . $ip . ':' . ($nomorRumah ?? 'guest');
    }
}
