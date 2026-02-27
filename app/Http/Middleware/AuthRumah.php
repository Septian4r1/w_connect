<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Rumah;

class AuthRumah
{
    public function handle(Request $request, Closure $next): Response
    {
        /*
        ===============================
        1. CEK SESSION LOGIN
        ===============================
        Ambil ID rumah dari session
        */
        $rumahId = $request->session()->get('rumah_id');

        if (!$rumahId) {
            // PERBAIKAN: cek apakah request ini AJAX
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Silakan login terlebih dahulu',
                    'redirect' => route('showlogin')
                ], 401);
            }

            return redirect()->route('showlogin');
        }

        /*
        ===============================
        2. CEK STATUS LOGIN (QUERY CEPAT)
        Hanya ambil 1 kolom saja untuk efisiensi
        ===============================
        */
        $statusLogin = Rumah::where('id', $rumahId)->value('status_login');

        /*
        ===============================
        3. JIKA RUMAH TIDAK ADA
        ===============================
        */
        if (!$statusLogin) {
            $request->session()->forget('rumah_id');

            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Nomor rumah tidak ditemukan',
                    'redirect' => route('showlogin')
                ], 401);
            }

            return redirect()->route('showlogin');
        }

        /*
        ===============================
        4. JIKA STATUS OFFLINE
        Logout paksa
        ===============================
        */
        if ($statusLogin !== 'online') {
            $request->session()->forget('rumah_id');

            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Session sudah berakhir',
                    'redirect' => route('showlogin')
                ], 401);
            }

            return redirect()
                ->route('showlogin')
                ->with('error', 'Session sudah berakhir');
        }

        /*
        ===============================
        5. LANJUTKAN REQUEST
        ===============================
        */
        $response = $next($request);

        /*
        ===============================
        6. MATIKAN CACHE BROWSER
        Anti tombol BACK login
        ===============================
        */
        return $response
            ->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
}
