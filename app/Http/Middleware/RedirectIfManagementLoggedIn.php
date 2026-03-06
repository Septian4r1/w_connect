<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\UserToken;

class RedirectIfManagementLoggedIn
{
    public function handle(Request $request, Closure $next): Response
    {

        $token = $request->session()->get('login_token');

        if ($token) {

            $session = UserToken::where('token', $token)->first();

            if ($session) {
                return redirect()->route('management.dashboard');
            }

        }

        return $next($request);
    }
}
