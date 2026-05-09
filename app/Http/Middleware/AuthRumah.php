<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Notifications\DatabaseNotification;
use App\Models\Rumah;

class AuthRumah
{
    /**
     * Handle an incoming request.
     *
     * Middleware ini melakukan beberapa hal:
     * 1. Cek session rumah_id (login)
     * 2. Validasi status login rumah
     * 3. Ambil 5 notifikasi terakhir untuk rumah (unread)
     * 4. Simpan notifikasi di session untuk Blade
     * 5. Nonaktifkan cache browser
     * 6. Lanjutkan request
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
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
            // Jika AJAX request, return JSON
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Silakan login terlebih dahulu',
                    'redirect' => route('showlogin')
                ], 401);
            }

            // Jika request biasa, redirect ke halaman login
            return redirect()->route('showlogin');
        }

        /*
        ===============================
        2. CEK STATUS LOGIN (QUERY CEPAT)
        ===============================
        Ambil hanya kolom status_login untuk efisiensi
        */
        $statusLogin = Rumah::where('id', $rumahId)->value('status_login');

        /*
        ===============================
        3. JIKA RUMAH TIDAK ADA
        ===============================
        Hapus session dan redirect
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
        ===============================
        Logout paksa
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
        5. AMBIL NOTIFIKASI TERAKHIR UNTUK RUMAH
        ===============================
        Ambil 5 notifikasi terakhir yang belum dibaca (unread)
        Langsung dari tabel notifications, notifiable_type = Rumah::class
        */
        if ($rumahId) {
            $initialNotifs = DatabaseNotification::where('notifiable_type', 'App\Models\Rumah')
                ->where('notifiable_id', $rumahId)
                ->whereNull('read_at') // hanya unread
                ->latest()
                ->take(5)
                ->get()
                ->map(function ($n) {
                    $data = $n->data;
                    return [
                        'id' => $n->id,
                        'no_pengajuan' => $data['no_pengajuan'] ?? null,
                        'message' => $data['message'] ?? $data['title'] ?? 'Tidak ada pesan',
                        'created_at' => $n->created_at->format('d M Y H:i')
                    ];
                })
                ->toArray();

            $request->session()->put('initial_notifications', $initialNotifs);

            //dd($initialNotifs);
        }
        /*
        ===============================
        6. LANJUTKAN REQUEST
        ===============================
        */
        $response = $next($request);

        /*
        ===============================
        7. NONAKTIFKAN CACHE BROWSER
        ===============================
        Mencegah tombol BACK login
        */
        return $response
            ->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
}
