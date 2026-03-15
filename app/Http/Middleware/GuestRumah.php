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
        /*
        ==========================================
        Ambil session rumah_id
        ==========================================
        */

        $rumahId = $request->session()->get('rumah_id');

        if ($rumahId) {

            /*
            ==========================================
            Ambil status login saja (query ringan)
            ==========================================
            */

            $statusLogin = Rumah::where('id', $rumahId)
                ->value('status_login');


            /*
            ==========================================
            Jika user sudah login
            ==========================================
            */

            if ($statusLogin === 'online') {

                // hindari redirect loop
                if (!$request->routeIs('homeWarga')) {
                    return redirect()->route('homeWarga');
                }
            }


            /*
            ==========================================
            Jika session ada tapi status offline
            ==========================================
            */

            if ($statusLogin !== 'online') {

                $request->session()->forget('rumah_id');
            }
        }

        return $next($request);
    }
}
