<?php

namespace App\Http\Controllers\Frontend\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Warga;
use App\Models\Rumah;

class SettingPasswordController extends Controller
{
    public function index(Request $request)
    {
        /**
         * ⚡ FAST SESSION CHECK (NO DB HIT)
         */
        $rumahId = $request->session()->get('rumah_id');

        if (!is_numeric($rumahId)) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('showlogin')
                ->with('error', 'Session tidak valid');
        }

        /**
         * 🛡 SECURITY HEADER HARDENING
         */
        return response()
            ->view('frontend.data_warga.data_pribadi.setting_password')
            ->header('X-Frame-Options', 'SAMEORIGIN')
            ->header('X-Content-Type-Options', 'nosniff')
            ->header('X-XSS-Protection', '1; mode=block')
            ->header('Referrer-Policy', 'strict-origin-when-cross-origin')
            ->header('Content-Security-Policy', "default-src 'self' https: data: 'unsafe-inline' 'unsafe-eval'");
    }

    public function verify_data(Request $request)
    {
        /**
         * ⚡ SESSION CHECK
         */
        $rumahId = session('rumah_id');

        if (!is_numeric($rumahId)) {
            return redirect()->route('showlogin')
                ->with('error', 'Session login hilang');
        }

        /**
         * 🛡 VALIDATION HARDENED
         */
        $validated = $request->validate([
            'nik' => ['required', 'digits_between:8,20']
        ]);

        /**
         * 🚀 ULTRA FAST EXISTS QUERY (INDEX FRIENDLY)
         * NO SELECT *
         * NO MODEL LOAD
         * NO N+1 POSSIBILITY
         */
        $exists = DB::table('wargas')
            ->join('keluargas', 'wargas.keluarga_id', '=', 'keluargas.id')
            ->where('keluargas.rumah_id', $rumahId)
            ->where('wargas.nik', $validated['nik'])
            ->where('wargas.status', 'aktif')
            ->limit(1)
            ->exists();

        if (!$exists) {
            return back()->with('error', 'NIK tidak ditemukan di data rumah anda');
        }

        /**
         * 🔐 SESSION STEP FLAG
         */
        session([
            'verified_step_password' => true,
            'verified_nik' => $validated['nik']
        ]);

        return redirect()->route('password.baru');
    }

    public function password_baru(Request $request)
    {
        /**
         * 🛡 STEP VALIDATION
         */
        if (!session('verified_step_password')) {
            return redirect()->route('setting.password')
                ->with('error', 'Silakan verifikasi NIK terlebih dahulu');
        }

        return view('frontend.data_warga.data_pribadi.input_password_baru');
    }

    public function simpan_password(Request $request)
    {
        /**
         * 🔐 STEP SESSION VALIDATION
         */
        if (!session('verified_step_password')) {
            return redirect()->route('showlogin')
                ->with('error', 'Session verifikasi tidak valid');
        }

        $rumahId = session('rumah_id');

        if (!is_numeric($rumahId)) {
            return redirect()->route('showlogin')
                ->with('error', 'Session login hilang');
        }

        /**
         * 🛡 PASSWORD VALIDATION (STRONGER)
         */
        $validated = $request->validate([
            'password' => [
                'required',
                'string',
                'min:6',
                'max:100',
                'confirmed'
            ]
        ]);

        /**
         * 🚀 ATOMIC UPDATE (NO RACE CONDITION)
         * NO MODEL LOAD
         * NO MEMORY OVERHEAD
         */
        $updated = DB::table('rumahs')
            ->where('id', $rumahId)
            ->limit(1)
            ->update([
                'password' => Hash::make($validated['password']),
                'status_login' => 'offline',
                'remember_token' => null,
                'updated_at' => now()
            ]);

        if (!$updated) {
            return back()->with('error', 'Gagal update password');
        }

        /**
         * 🧹 CLEAR STEP SESSION
         */
        session()->forget([
            'verified_step_password',
            'verified_nik'
        ]);

        /**
         * 🔐 FORCE TOTAL LOGOUT
         */
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('showlogin')
            ->with('success', 'Password berhasil diperbarui, silakan login kembali');
    }
}
