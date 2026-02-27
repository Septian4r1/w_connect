<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Rumah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * ==============================
     * 1. TAMPILKAN FORM LOGIN
     * ==============================
     */
    public function showLogin()
    {
        return view('frontend.auth.login');
    }

    /**
     * ==============================
     * 2. PROSES LOGIN USER
     * ==============================
     */
    public function login(Request $request)
    {
        // VALIDASI INPUT
        $request->validate([
            'nomor_rumah' => 'required|string|max:20',
            'password'    => 'required|string|max:100'
        ]);

        // BERSIHKAN INPUT
        $nomorRumah = strtoupper(trim($request->nomor_rumah));
        $password   = trim($request->password);

        // AMBIL DATA RUMAH
        $rumah = Rumah::select('id', 'password', 'status_login', 'nomor_rumah')
            ->where('nomor_rumah', $nomorRumah)
            ->first();

        // CEK NOMOR RUMAH
        if (!$rumah) {
            return $this->response($request, 'error', 'Nomor rumah tidak ditemukan', 404);
        }

        // CEK PASSWORD
        if (!Hash::check($password, $rumah->password)) {
            return $this->response($request, 'error', 'Password salah', 401);
        }

        // CEK DOUBLE LOGIN
        if ($rumah->status_login === 'online') {
            // Pesan khusus untuk double login
            $message = "Apakah benar akun Anda sedang login di perangkat lain?\n\n" .
                "Jika bukan Anda, segera logout semua perangkat dan ganti password.";
            return $this->response(
                $request,
                'warning',
                $message,
                403,
                route('logoutAllDevices', ['id' => $rumah->id])
            );
        }

        // UPDATE STATUS LOGIN MENJADI ONLINE
        $updated = Rumah::where('id', $rumah->id)
            ->where('status_login', 'offline')
            ->update([
                'status_login' => 'online',
                'updated_at'   => now()
            ]);

        if (!$updated) {
            // Jika bentrok saat update status, tetap kirim pesan double login
            $message = "Akun sedang login di perangkat lain.\n\n" .
                "Jika bukan Anda, segera logout semua perangkat dan ganti password.";
            return $this->response(
                $request,
                'warning',
                $message,
                403,
                route('logoutAllDevices', ['id' => $rumah->id])
            );
        }

        // LOGIN BERHASIL
        $request->session()->put('rumah_id', $rumah->id);
        return $this->response($request, 'success', 'Login berhasil', 200, route('homeWarga'));
    }

    /**
     * ==============================
     * 3. LOGOUT
     * ==============================
     */
    public function logout(Request $request)
    {
        // Ambil rumah_id dari session atau input
        $rumahId = $request->session()->get('rumah_id');

        // Jika ada input nomor_rumah tapi session kosong, ambil dari DB
        if (!$rumahId && $request->input('nomor_rumah')) {
            $rumah = Rumah::where('nomor_rumah', $request->input('nomor_rumah'))->first();
            $rumahId = $rumah?->id;
        }

        // Jika rumah ada, panggil logoutAllDevices
        if ($rumahId) {
            return $this->logoutAllDevices($rumahId, $request);
        }

        // Jika tidak ada rumah, hapus session tetap
        $request->session()->forget('rumah_id');

        return redirect()->route('showlogin')
            ->with('success', 'Anda berhasil logout.');
    }

    /**
     * ==============================
     * 4. LOGOUT SEMUA PERANGKAT
     * ==============================
     */
    public function logoutAllDevices($id, Request $request)
    {
        // Update status login menjadi offline
        Rumah::where('id', $id)->update(['status_login' => 'offline']);

        // Hapus semua session login rumah
        $request->session()->forget('rumah_id');

        // Redirect ke halaman login dengan pesan sukses
        return redirect()->route('showlogin')
            ->with('success', 'Semua perangkat berhasil logout. Silakan login kembali dan ganti password jika perlu.');
    }
    /**
     * ==============================
     * RESPONSE HELPER
     * ==============================
     */
    private function response(Request $request, string $status, string $message, int $code = 200, ?string $redirect = null)
    {
        if ($request->ajax()) {
            $response = [
                'status'  => $status,
                'message' => $message
            ];
            if ($redirect) {
                $response['redirect'] = $redirect;
            }
            return response()->json($response, $code);
        }

        if ($status === 'success' && $redirect) {
            return redirect($redirect)->with('message', $message);
        }

        return back()->withInput()->withErrors(['nomor_rumah' => $message]);
    }
}
