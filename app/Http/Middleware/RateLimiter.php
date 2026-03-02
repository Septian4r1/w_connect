<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class RateLimiter
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, $next)
    {
        $limit = 3;
        $minutes = 15;
        $ip = $request->ip();
        $key = 'login_rate:' . $ip;
        $attempts = Cache::get($key, 0);

        if ($attempts >= $limit) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Terlalu banyak percobaan login. Silakan tunggu {$minutes} menit."
                ], 429); // 429 Too Many Requests
            }

            return redirect()->route('showlogin')
                ->with('error', "Terlalu banyak percobaan login. Silakan tunggu {$minutes} menit.");
        }

        Cache::put($key, $attempts + 1, now()->addMinutes($minutes));
        Cache::put($key . '_expires', now()->addMinutes($minutes)->timestamp, now()->addMinutes($minutes));

        return $next($request);
    }

    // Method reset harus static
    public static function reset($ip)
    {
        Cache::forget('login_rate:' . $ip);
        Cache::forget('login_rate:' . $ip . '_expires');
    }
}
