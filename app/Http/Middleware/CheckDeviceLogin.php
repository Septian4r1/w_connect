<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\UserToken;
use Carbon\Carbon;

class CheckDeviceLogin
{
    public function handle(Request $request, Closure $next): Response
    {

        /**
         * 1️⃣ Ambil token dari SESSION
         */
        $token = $request->session()->get('login_token');

        if (!$token) {
            return redirect()->route('showlogin_management')
                ->with('error', 'Token tidak ditemukan');
        }

        /**
         * 2️⃣ Cek token di database
         */
        $session = UserToken::where('token', $token)->first();

        if (!$session) {
            return redirect()->route('showlogin_management')
                ->with('error', 'Session tidak valid');
        }

        /**
         * 3️⃣ Cek expired token
         */
        if ($session->expires_at && Carbon::now()->greaterThan($session->expires_at)) {

            $session->delete();

            return redirect()->route('showlogin_management')
                ->with('error', 'Session sudah expired');
        }

        /**
         * 4️⃣ Inject user_id ke request
         */
        $request->merge([
            'auth_user_id' => $session->user_id
        ]);

        return $next($request);
    }
}
