<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserOtp;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use App\Mail\SendOtpMail;

class OtpService
{
    /**
     * Generate & kirim OTP ke email user
     */
    public function generateAndSendOtp($user, $request)
    {
        $otp = random_int(100000, 999999);
        $hashedOtp = Hash::make($otp);

        $fingerprint = hash(
            'sha256',
            $request->ip() .
                $request->userAgent() .
                $request->header('accept-language')
        );

        UserOtp::updateOrCreate(
            ['user_id' => $user->id],
            [
                'otp' => $hashedOtp,
                'expired_at' => now()->addMinutes(5),
                'device_fingerprint' => $fingerprint
            ]
        );

        Mail::to($user->email)->send(new SendOtpMail($otp));
    }

    /**
     * Verifikasi OTP
     */
    public function verifyOtp($email, $otp)
    {
        $user = User::where('email', $email)->firstOrFail();
        $key = 'otp-attempt:' . $user->id;

        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            $minutes = ceil($seconds / 60);
            abort(429, "Terlalu banyak OTP salah. Tunggu {$minutes} menit.");
        }

        $otpData = UserOtp::where('user_id', $user->id)->first();
        if (!$otpData) abort(401, 'OTP tidak ditemukan');
        if ($otpData->expired_at < now()) abort(401, 'OTP expired');

        if (!Hash::check($otp, $otpData->otp)) {
            RateLimiter::hit($key, 900);
            abort(401, 'OTP salah');
        }

        // ✅ HAPUS OTP
        $otpData->delete();
        RateLimiter::clear($key);

        // 🔥 INI WAJIB ADA
        $updated = $user->forceFill([
            'email_verified_at' => now()
        ])->save();

        // DEBUG (sementara)
        if (!$updated) {
            throw new \Exception('Gagal update email_verified_at');
        }

        return $user->fresh(); // ambil data terbaru dari DB
    }
}
