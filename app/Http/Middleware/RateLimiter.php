<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Cache;

class RateLimiter
{

    /*
    ======================================================
    CEK APAKAH LOGIN SUDAH MELEBIHI BATAS
    ======================================================
    */

    public static function tooManyAttempts($ip, $nomorRumah = null)
    {

        $limit = 3;

        $key = self::key($ip, $nomorRumah);

        $attempts = Cache::get($key, 0);

        return $attempts >= $limit;
    }



    /*
    ======================================================
    TAMBAH JUMLAH PERCOBAAN LOGIN
    ======================================================
    */

    public static function hit($ip, $nomorRumah = null)
    {

        $minutes = 15;

        $key = self::key($ip, $nomorRumah);

        $attempts = Cache::get($key, 0);

        Cache::put(
            $key,
            $attempts + 1,
            now()->addMinutes($minutes)
        );
    }



    /*
    ======================================================
    RESET RATE LIMIT JIKA LOGIN BERHASIL
    ======================================================
    */

    public static function reset($ip, $nomorRumah = null)
    {

        $key = self::key($ip, $nomorRumah);

        Cache::forget($key);
    }



    /*
    ======================================================
    GENERATE KEY CACHE
    ======================================================
    */

    private static function key($ip, $nomorRumah)
    {
        return 'login_rate:' . $ip . ':' . $nomorRumah;
    }
}
