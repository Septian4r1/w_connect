<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAjaxRequest
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->ajax() && !$request->expectsJson()) {

            abort(403, 'Direct access not allowed');
        }

        return $next($request);
    }
}
