<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserOtp;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use App\Mail\SendOtpMail;
use Illuminate\Http\Request;

class OtpService
{
    /**
     * Generate & kirim OTP
     */
    public function generateAndSendOtp(
        User $user,
        Request $request,
        ?string $type = null
    ): void {

        /**
         * 🔥 DEFAULT TYPE
         */
        $type = $type ?? 'login';

        /**
         * 🔥 GENERATE OTP
         */
        $otp = random_int(100000, 999999);

        $hashedOtp = Hash::make($otp);

        /**
         * 🔥 DEVICE FINGERPRINT
         */
        $fingerprint = hash(
            'sha256',
            $request->ip() .
                $request->userAgent() .
                $request->header('accept-language')
        );

        /**
         * 🔥 SAVE OTP
         */
        UserOtp::updateOrCreate(

            [
                'user_id' => $user->id,
                'type'    => $type
            ],

            [
                'otp'                => $hashedOtp,
                'expired_at'         => now()->addMinutes(5),
                'device_fingerprint' => $fingerprint,
            ]
        );

        /**
         * 🔥 SEND EMAIL
         */
        Mail::to($user->email)->send(
            new SendOtpMail(
                otp: $otp,
                user: $user,
                type: $type
            )
        );
    }

    /**
     * 🔥 VERIFY OTP
     */
    public function verifyOtp(
        string $email,
        string $otp,
        ?string $type = null
    ): User {

        $type = $type ?? 'login';

        $user = User::where('email', $email)
            ->firstOrFail();

        $key = 'otp-attempt:' . $user->id;

        /**
         * 🔥 RATE LIMIT
         */
        if (RateLimiter::tooManyAttempts($key, 3)) {

            $seconds = RateLimiter::availableIn($key);

            $minutes = ceil($seconds / 60);

            abort(
                429,
                "Terlalu banyak OTP salah. Tunggu {$minutes} menit."
            );
        }

        /**
         * 🔥 AMBIL OTP BERDASARKAN TYPE
         */
        $otpData = UserOtp::where('user_id', $user->id)
            ->where('type', $type)
            ->first();

        if (!$otpData) {
            abort(401, 'OTP tidak ditemukan');
        }

        /**
         * 🔥 EXPIRED
         */
        if ($otpData->expired_at < now()) {
            abort(401, 'OTP expired');
        }

        /**
         * 🔥 OTP SALAH
         */
        if (!Hash::check($otp, $otpData->otp)) {

            RateLimiter::hit($key, 900);

            abort(401, 'OTP salah');
        }

        /**
         * ✅ HAPUS OTP
         */
        $otpData->delete();

        RateLimiter::clear($key);

        /**
         * 🔥 OPTIONAL
         */
        $user->forceFill([
            'email_verified_at' => now()
        ])->save();

        return $user->fresh();
    }
}
