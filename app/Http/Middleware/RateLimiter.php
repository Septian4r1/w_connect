<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Support\LoginRateLimiter;

class RateLimiter
{
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();
        $nomorRumah = strtoupper(trim($request->input('nomor_rumah')));

        if (LoginRateLimiter::tooManyAttempts($ip, $nomorRumah)) {

            $minutes = LoginRateLimiter::remainingMinutes($ip, $nomorRumah);

            return response()->json([
                'status' => false,
                'message' => "Terlalu banyak percobaan login. Coba lagi dalam {$minutes} menit."
            ], 429);
        }

        return $next($request);
    }
}
