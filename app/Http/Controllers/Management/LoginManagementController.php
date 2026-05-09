<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\AuthService;
use App\Services\OtpService;
use App\Services\DeviceService;

class LoginManagementController extends Controller
{
    protected $authService;
    protected $otpService;
    protected $deviceService;

    public function __construct(
        AuthService $authService,
        OtpService $otpService,
        DeviceService $deviceService
    ) {
        $this->authService = $authService;
        $this->otpService = $otpService;
        $this->deviceService = $deviceService;
    }

    // Halaman login
    public function showLogin_management()
    {
        return view('backend.management.auth.login_management');
    }

    // Step 1: login dengan password → kirim OTP
    public function login_management(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        // Login password valid → generate OTP
        $user = $this->authService->loginWithPassword(
            $request->email,
            $request->password,
            $request
        );

        // Response JSON supaya frontend bisa menampilkan email & form OTP
        return response()->json([
            'status' => true,
            'message' => 'Password valid. Kode OTP sudah dikirim ke email.',
            'email' => $user->email,    // tampilkan email
            'otpSent' => true
        ]);
    }

    // Step 2: verifikasi OTP → login
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required'
        ]);

        $user = $this->otpService->verifyOtp($request->email, $request->otp);

        // Login session Laravel (jika diperlukan)
        Auth::login($user);
        $request->session()->regenerate();

        // Register device & dapatkan token
        $token = $this->deviceService->registerDevice($user, $request);
        $request->session()->put('login_token', $token);

        return response()->json([
            'status' => true,
            'message' => 'Login berhasil',
            'redirect' => route('management.dashboard'),
            'token' => $token // <-- kirim token ke frontend
        ]);
    }

    public function logout_management(Request $request)
    {
        if (Auth::check()) {

            /** @var \App\Models\User $user */
            $user = Auth::user();

            // Hapus token device
            $this->deviceService->logoutDevice($user->id);

            // Reset email verification (opsional - sesuai kebutuhan kamu)
            $user->forceFill([
                'email_verified_at' => null
            ])->save();
        }

        // Logout user
        Auth::logout();

        // Hapus session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('showlogin_management');
    }
}
