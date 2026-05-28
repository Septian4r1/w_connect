<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Warga;
use App\Models\Keluarga;
use App\Models\Rumah;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use Illuminate\Database\QueryException;

class ForgotPasswordController extends Controller
{
    public function showForgotPassword()
    {
        return view('frontend.auth.forgotpassword');
    }

    public function checkNik(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255'
        ]);

        $namaLengkap = trim($request->nama_lengkap);

        // Cari warga berdasarkan nama lengkap
        $wargaId = Warga::where('nama', $namaLengkap)
            ->where('status', 'aktif')
            ->value('id');

        if (!$wargaId) {
            return redirect()->route('forgotPassword')
                ->with('error', 'Nama lengkap tidak ditemukan atau belum aktif');
        }

        // Simpan ID warga di session
        Session::put('reset_warga_id', $wargaId);

        return redirect()->route('forgotPassword.checkUnit')
            ->with('success', 'Nama lengkap terverifikasi');
    }

    public function showCheckUnit()
    {
        if (!session()->has('reset_warga_id')) {
            return redirect()->route('forgotPassword');
        }

        return view('frontend.auth.check_unit');
    }

    public function verifikasiCheckUnit(Request $request)
    {
        $validated = $request->validate([
            'no_kk' => 'required|digits_between:10,20',
            'blok'  => 'required|string|max:20'
        ]);

        $noKk = trim($validated['no_kk']);
        $blok  = strtoupper(trim($validated['blok']));

        // Optimasi: ambil 2 kolom saja untuk cek KK dan rumah_id
        $keluarga = Keluarga::select('id', 'rumah_id')
            ->where('no_kk', $noKk)
            ->where('status', 'aktif')
            ->first();

        if (!$keluarga) {
            return back()->with('error', 'No KK tidak ditemukan atau belum aktif');
        }

        // Ambil rumah hanya 1 baris
        $rumah = Rumah::select('id', 'nomor_rumah')
            ->find($keluarga->rumah_id);

        if (!$rumah) {
            return back()->with('error', 'Data rumah tidak ditemukan');
        }

        if (strtoupper($rumah->nomor_rumah) !== $blok) {
            return back()->with('error', 'Nomor rumah tidak sesuai');
        }

        // Simpan session reset dengan timestamp
        session([
            'reset_keluarga_id' => $keluarga->id,
            'reset_rumah_id'    => $rumah->id,
            'reset_verified_at' => now()
        ]);

        return redirect()
            ->route('forgotPassword.newPassword')
            ->with('success', 'Data berhasil diverifikasi');
    }

    public function showNewPassword()
    {
        $this->validateResetSession();

        return view('frontend.auth.new_password');
    }

    public function saveNewPassword(Request $request)
    {
        $this->validateResetSession();

        $validated = $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $rumah = Rumah::find(session('reset_rumah_id'));

                if (!$rumah) {
                    // ❌ Jangan QueryException
                    throw new \Exception('Data rumah tidak ditemukan');
                }

                $rumah->update([
                    'password'       => $validated['password'],
                    'remember_token' => null,
                    'status_login'   => 'offline'
                ]);
            });

            session()->forget([
                'reset_keluarga_id',
                'reset_rumah_id',
                'reset_verified_at'
            ]);

            return redirect()
                ->route('showlogin')
                ->with('success', 'Password berhasil diperbarui, silakan login.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage() ?: 'Terjadi kesalahan sistem, silakan coba lagi.');
        }
    }

    /**
     * Validasi session reset dan expired (5 menit)
     */
    protected function validateResetSession()
    {
        if (!session()->has('reset_rumah_id') || !session()->has('reset_verified_at')) {
            redirect()->route('forgotPassword')
                ->with('error', 'Sesi reset tidak valid, silakan verifikasi ulang')
                ->send();
            exit;
        }

        if (now()->diffInMinutes(session('reset_verified_at')) > 5) {
            session()->forget([
                'reset_keluarga_id',
                'reset_rumah_id',
                'reset_verified_at'
            ]);

            redirect()->route('forgotPassword')
                ->with('error', 'Sesi reset telah berakhir, silakan ulangi.')
                ->send();
            exit;
        }
    }
}
