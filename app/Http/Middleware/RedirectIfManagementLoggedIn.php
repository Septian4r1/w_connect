<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\UserToken;

class RedirectIfManagementLoggedIn
{
    public function handle(Request $request, Closure $next): Response
    {
        // ✅ 1. Prioritas: cek Laravel Auth (paling reliable)
        if (Auth::check()) {
            return redirect()->route('management.dashboard');
        }

        // ✅ 2. Fallback: cek token di session
        $token = $request->session()->get('login_token');

        if ($token) {
            $exists = UserToken::where('token', $token)
                ->where('expired_at', '>', now()) // 🔥 pastikan belum expired
                ->exists();

            if ($exists) {
                return redirect()->route('management.dashboard');
            }

            // ❗ cleanup kalau token sudah tidak valid
            $request->session()->forget('login_token');
        }

        return $next($request);
    }
}
