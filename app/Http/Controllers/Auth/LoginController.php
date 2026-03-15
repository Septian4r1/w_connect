<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Rumah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Http\Middleware\RateLimiter;

class LoginController extends Controller
{

    /*
    ======================================================
    1. MENAMPILKAN HALAMAN LOGIN
    ======================================================
    */

    public function showLogin()
    {
        return view('frontend.auth.login');
    }



    /*
    ======================================================
    2. PROSES LOGIN USER
    ======================================================
    */

    public function login(Request $request)
    {

        /*
        ======================================================
        VALIDASI INPUT
        ======================================================
        Melindungi dari input yang tidak sesuai format
        */

        $request->validate([
            'nomor_rumah' => 'required|string|max:20',
            'password'    => 'required|string|max:100'
        ]);



        /*
        ======================================================
        SANITASI INPUT
        ======================================================
        - trim() menghapus spasi
        - strip_tags() mencegah script injection
        - strtoupper() menyamakan format nomor rumah
        */

        $nomorRumah = strtoupper(
            strip_tags(
                trim($request->input('nomor_rumah'))
            )
        );

        $password = trim($request->input('password'));


        /*
======================================================
CEK RATE LIMIT LOGIN
======================================================
Mencegah brute force login
*/

        if (RateLimiter::tooManyAttempts($request->ip(), $nomorRumah)) {

            return $this->response(
                $request,
                'error',
                'Terlalu banyak percobaan login. Tunggu 15 menit.',
                429
            );
        }



        /*
        ======================================================
        AMBIL DATA RUMAH
        ======================================================
        Query ringan hanya mengambil kolom penting
        */

        $rumah = Rumah::select(
            'id',
            'password',
            'status_login',
            'nomor_rumah'
        )
            ->where('nomor_rumah', $nomorRumah)
            ->first();



        /*
        ======================================================
        CEK NOMOR RUMAH
        ======================================================
        */

        if (!$rumah) {

            RateLimiter::hit($request->ip(), $nomorRumah);

            return $this->response(
                $request,
                'error',
                'Nomor rumah tidak ditemukan',
                404
            );
        }



        /*
        ======================================================
        CEK PASSWORD
        ======================================================
        Password dicek menggunakan Hash Laravel
        */

        if (!Hash::check($password, $rumah->password)) {

            RateLimiter::hit($request->ip(), $nomorRumah);

            return $this->response(
                $request,
                'error',
                'Password salah',
                401
            );
        }



        /*
        ======================================================
        CEK DOUBLE LOGIN
        ======================================================
        Jika akun sedang login di perangkat lain
        */

        if ($rumah->status_login === 'online') {

            $message =
                "Akun ini sedang login di perangkat lain.\n\n" .
                "Jika bukan Anda, segera logout semua perangkat dan ganti password.";

            return $this->response(
                $request,
                'warning',
                $message,
                403,
                route('logoutAllDevices', ['id' => $rumah->id])
            );
        }



        /*
        ======================================================
        UPDATE STATUS LOGIN
        ======================================================
        Proteksi race condition:
        hanya update jika status masih offline
        */

        $updated = Rumah::where('id', $rumah->id)
            ->where('status_login', 'offline')
            ->update([
                'status_login' => 'online',
                'updated_at' => now()
            ]);



        /*
        ======================================================
        PROTEKSI DOUBLE LOGIN RACE CONDITION
        ======================================================
        */

        if (!$updated) {

            $message =
                "Akun sedang login di perangkat lain.\n\n" .
                "Jika bukan Anda, segera logout semua perangkat.";

            return $this->response(
                $request,
                'warning',
                $message,
                403,
                route('logoutAllDevices', ['id' => $rumah->id])
            );
        }


        /*
======================================================
RESET RATE LIMIT JIKA LOGIN BERHASIL
======================================================
*/

        RateLimiter::reset($request->ip(), $nomorRumah);



        /*
        ======================================================
        PROTEKSI SESSION FIXATION
        ======================================================
        */

        Session::regenerate();



        /*
        ======================================================
        SIMPAN SESSION LOGIN
        ======================================================
        */

        $request->session()->put('rumah_id', $rumah->id);



        /*
        ======================================================
        LOGIN BERHASIL
        ======================================================
        */

        return $this->response(
            $request,
            'success',
            'Login berhasil',
            200,
            route('homeWarga')
        );
    }



    /*
    ======================================================
    3. LOGOUT USER
    ======================================================
    */

    public function logout(Request $request)
    {

        $rumahId = $request->session()->get('rumah_id');



        /*
        Jika session tidak ada,
        cek nomor rumah dari request
        */

        if (!$rumahId && $request->input('nomor_rumah')) {

            $nomorRumah = strip_tags(
                trim($request->input('nomor_rumah'))
            );

            $rumah = Rumah::where('nomor_rumah', $nomorRumah)->first();

            $rumahId = $rumah?->id;
        }



        /*
        Jika rumah ditemukan
        logout semua perangkat
        */

        if ($rumahId) {
            return $this->logoutAllDevices($rumahId, $request);
        }



        /*
        Jika tidak ditemukan
        hapus session saja
        */

        $request->session()->forget('rumah_id');

        return redirect()
            ->route('showlogin')
            ->with('success', 'Anda berhasil logout.');
    }



    /*
    ======================================================
    4. LOGOUT SEMUA PERANGKAT
    ======================================================
    */

    public function logoutAllDevices($id, Request $request)
    {

        /*
        Update status login menjadi offline
        */

        Rumah::where('id', $id)->update([
            'status_login' => 'offline'
        ]);



        /*
        Hapus semua session
        */

        $request->session()->invalidate();
        $request->session()->regenerateToken();



        return redirect()
            ->route('showlogin')
            ->with(
                'success',
                'Semua perangkat berhasil logout. Silakan login kembali.'
            );
    }



    /*
    ======================================================
    RESPONSE HELPER
    ======================================================
    Mengatur response untuk:
    - AJAX
    - Redirect
    - Error
    */

    private function response(
        Request $request,
        string $status,
        string $message,
        int $code = 200,
        ?string $redirect = null
    ) {

        /*
        Response AJAX
        */

        if ($request->ajax()) {

            $response = [
                'status' => $status,
                'message' => $message
            ];

            if ($redirect) {
                $response['redirect'] = $redirect;
            }

            return response()->json($response, $code);
        }



        /*
        Redirect jika sukses
        */

        if ($status === 'success' && $redirect) {

            return redirect($redirect)
                ->with('message', $message);
        }



        /*
        Response error
        */

        return back()
            ->withInput()
            ->withErrors([
                'nomor_rumah' => $message
            ]);
    }
}
