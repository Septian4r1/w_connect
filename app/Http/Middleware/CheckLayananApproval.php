<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Rumah;
use Illuminate\Support\Facades\Cache;

class CheckLayananApproval
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $rumahId = $request->session()->get('rumah_id');

        if ($rumahId) {

            $layananApprovalPending = Cache::remember(
                "layanan_approval_{$rumahId}",
                300,
                function () use ($rumahId) {
                    $rumah = Rumah::select('id', 'layanan_approval')->find($rumahId);
                    return $rumah && $rumah->layanan_approval == 0;
                }
            );

            // Share ke view (untuk modal)
            view()->share('layananApprovalPending', $layananApprovalPending);

            /*
        ======================================================
        🔥 BLOCK SEMUA ROUTE JIKA BELUM APPROVAL
        ======================================================
        */
            if ($layananApprovalPending) {

                // Izinkan hanya route approval saja
                if (
                    !$request->routeIs('homeWarga') &&
                    !$request->routeIs('setujuLayanan')
                ) {
                    return redirect()->route('homeWarga');
                }
            }
        }

        return $next($request);
    }
}
