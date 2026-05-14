<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserOtp;
use App\Mail\SendOtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

class OtpService
{
    /**
     * =========================================================
     * OTP TYPES (CONSTANTS)
     * =========================================================
     * Gunakan ini di semua controller agar konsisten
     * =========================================================
     */
    public const TYPE_LOGIN                 = 'login';
    public const TYPE_RESET_PASSWORD        = 'reset_password';
    public const TYPE_RESET_FORGOT_PASSWORD = 'reset_forgot_password';

    /**
     * =========================================================
     * GENERATE & SEND OTP
     * =========================================================
     */
    public function generateAndSendOtp(
        User $user,
        Request $request,
        ?string $type = null
    ): void {

        /**
         * =========================================
         * DEFAULT TYPE
         * =========================================
         */
        $type = $type ?? self::TYPE_LOGIN;

        /**
         * =========================================
         * GENERATE OTP 6 DIGIT
         * =========================================
         */
        $otp = random_int(100000, 999999);

        $hashedOtp = Hash::make($otp);

        /**
         * =========================================
         * DEVICE FINGERPRINT
         * =========================================
         */
        $fingerprint = hash(
            'sha256',
            $request->ip() .
                $request->userAgent() .
                ($request->header('accept-language') ?? '')
        );

        /**
         * =========================================
         * STORE / UPDATE OTP
         * =========================================
         */
        UserOtp::updateOrCreate(

            [
                'user_id' => $user->id,
                'type'    => $type,
            ],

            [
                'otp'                => $hashedOtp,
                'expired_at'         => now()->addMinutes(5),
                'device_fingerprint' => $fingerprint,
            ]
        );

        /**
         * =========================================
         * SEND EMAIL OTP
         * =========================================
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
     * =========================================================
     * VERIFY OTP
     * =========================================================
     */
    public function verifyOtp(
        string $email,
        string $otp,
        ?string $type = null
    ): User {

        /**
         * =========================================
         * DEFAULT TYPE
         * =========================================
         */
        $type = $type ?? self::TYPE_LOGIN;

        /**
         * =========================================
         * FIND USER
         * =========================================
         */
        $user = User::where('email', $email)->firstOrFail();

        /**
         * =========================================
         * RATE LIMIT KEY
         * =========================================
         */
        $key = 'otp-attempt:' . $user->id;

        /**
         * =========================================
         * RATE LIMIT CHECK
         * =========================================
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
         * =========================================
         * GET OTP BY TYPE
         * =========================================
         */
        $otpData = UserOtp::where('user_id', $user->id)
            ->where('type', $type)
            ->first();

        if (!$otpData) {
            abort(401, 'OTP tidak ditemukan');
        }

        /**
         * =========================================
         * CHECK EXPIRED
         * =========================================
         */
        if ($otpData->expired_at < now()) {
            abort(401, 'OTP expired');
        }

        /**
         * =========================================
         * CHECK OTP MATCH
         * =========================================
         */
        if (!Hash::check($otp, $otpData->otp)) {

            RateLimiter::hit($key, 900); // 15 menit penalty

            abort(401, 'OTP salah');
        }

        /**
         * =========================================
         * SUCCESS → DELETE OTP
         * =========================================
         */
        $otpData->delete();

        RateLimiter::clear($key);

        /**
         * =========================================
         * OPTIONAL: MARK EMAIL VERIFIED
         * =========================================
         */
        $user->forceFill([
            'email_verified_at' => now()
        ])->save();

        return $user->fresh();
    }
}
