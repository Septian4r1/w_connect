<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use App\Services\OtpService;

class AuthService
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    /**
     * Step 1: Validasi login (email + password)
     * Kirim OTP jika password valid
     */
    public function loginWithPassword($email, $password, $request)
    {
        $ip = $request->ip();
        $key = 'login-attempt:' . $ip;

        // 1️⃣ Cek terlalu banyak percobaan login
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            $minutes = ceil($seconds / 60);
            abort(429, "Terlalu banyak login. Tunggu {$minutes} menit.");
        }

        // 2️⃣ Ambil user
        $user = User::where('email', $email)->first();

        // 3️⃣ Validasi password
        if (!$user || !Hash::check($password, $user->password)) {
            RateLimiter::hit($key, 900); // simpan percobaan gagal 15 menit
            abort(401, 'Email atau password salah');
        }

        // 4️⃣ Kirim OTP untuk verifikasi login
        $this->otpService->generateAndSendOtp($user, $request);

        // 5️⃣ Reset login attempt
        RateLimiter::clear($key);

        return $user;
    }
}
