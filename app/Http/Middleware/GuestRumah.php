<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Rumah;

class GuestRumah
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Ambil session rumah_id
        $rumahId = $request->session()->get('rumah_id');

        if ($rumahId) {

            // 2. Ambil status_login saja, tanpa load object Eloquent penuh
            $statusLogin = Rumah::where('id', $rumahId)
                ->value('status_login');

            // 3. Jika online, redirect ke home
            if ($statusLogin === 'online') {
                return redirect()->route('homeWarga');
            }

            // Optional: session masih ada tapi status_login offline → hapus session
            if ($statusLogin === null || $statusLogin !== 'online') {
                $request->session()->forget('rumah_id');
            }
        }

        return $next($request);
    }
}
