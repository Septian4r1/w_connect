<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Rumah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Support\LoginRateLimiter;

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
        */

        $request->validate([
            'nomor_rumah' => 'required|string|max:20',
            'password'    => 'required|string|max:100'
        ]);


        /*
        ======================================================
        SANITASI INPUT
        ======================================================
        */

        $nomorRumah = strtoupper(
            strip_tags(
                trim($request->input('nomor_rumah'))
            )
        );

        $password = trim($request->input('password'));
        $ip = $request->ip();


        /*
        ======================================================
        CEK RATE LIMIT LOGIN
        ======================================================
        */

        if (LoginRateLimiter::tooManyAttempts($ip, $nomorRumah)) {

            return $this->response(
                $request,
                'error',
                'Terlalu banyak percobaan login. Coba lagi dalam 15 menit.',
                429
            );
        }


        /*
        ======================================================
        AMBIL DATA RUMAH
        ======================================================
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

            LoginRateLimiter::hit($ip, $nomorRumah);

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
        */

        if (!Hash::check($password, $rumah->password)) {

            LoginRateLimiter::hit($ip, $nomorRumah);

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
        UPDATE STATUS LOGIN (RACE CONDITION SAFE)
        ======================================================
        */

        $updated = Rumah::where('id', $rumah->id)
            ->where('status_login', 'offline')
            ->update([
                'status_login' => 'online',
                'updated_at' => now()
            ]);

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

        LoginRateLimiter::reset($ip, $nomorRumah);


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

        if (!$rumahId && $request->input('nomor_rumah')) {

            $nomorRumah = strip_tags(
                trim($request->input('nomor_rumah'))
            );

            $rumah = Rumah::where('nomor_rumah', $nomorRumah)->first();

            $rumahId = $rumah?->id;
        }

        if ($rumahId) {
            return $this->logoutAllDevices($rumahId, $request);
        }

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
        Rumah::where('id', $id)->update([
            'status_login' => 'offline'
        ]);

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
    */

    private function response(
        Request $request,
        string $status,
        string $message,
        int $code = 200,
        ?string $redirect = null
    ) {
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

        if ($status === 'success' && $redirect) {

            return redirect($redirect)
                ->with('message', $message);
        }

        return back()
            ->withInput()
            ->withErrors([
                'nomor_rumah' => $message
            ]);
    }
}
