<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Keluarga;
use App\Models\Warga;

class CheckDataWarga
{
    public function handle(Request $request, Closure $next)
    {
        $rumahId = $request->session()->get('rumah_id');

        if (!$rumahId) {
            return redirect()->route('showlogin');
        }

        /*
    ====================================================
    🔥 TAMBAHKAN INI
    JANGAN CEK DATA JIKA BELUM APPROVAL
    ====================================================
    */

        $rumah = \App\Models\Rumah::select('layanan_approval')
            ->find($rumahId);

        if ($rumah && $rumah->layanan_approval == 0) {
            return $next($request);
        }

        /*
    ========================
    CEK DATA KELUARGA
    ========================
    */

        $keluargaAda = Keluarga::where('rumah_id', $rumahId)->exists();

        if (!$keluargaAda) {

            if (!$request->routeIs('keluarga.*')) {

                return redirect()->route('keluarga.create')
                    ->with('warning', 'Silakan isi data keluarga terlebih dahulu');
            }

            return $next($request);
        }

        /*
    ========================
    CEK DATA WARGA
    ========================
    */

        $wargaAda = Warga::whereExists(function ($q) use ($rumahId) {

            $q->selectRaw(1)
                ->from('keluargas')
                ->whereColumn('keluargas.id', 'wargas.keluarga_id')
                ->where('keluargas.rumah_id', $rumahId);
        })->exists();


        if (!$wargaAda) {

            if (!$request->routeIs('warga.*')) {

                return redirect()->route('warga.create')
                    ->with('warning', 'Silakan isi data warga terlebih dahulu');
            }

            return $next($request);
        }

        return $next($request);
    }
}
