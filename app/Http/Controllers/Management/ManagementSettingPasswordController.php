<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManagementSettingPasswordController extends Controller
{
    protected OtpService $otpService;

    public function __construct(
        OtpService $otpService
    ) {
        $this->otpService = $otpService;
    }

    /**
     * =========================================
     * HALAMAN VERIFIKASI
     * =========================================
     */
    public function index()
    {
        return view(
            'backend.layouts.settingpassword.index'
        );
    }

    /**
     * =========================================
     * STEP 1
     * CAPTCHA → KIRIM OTP
     * =========================================
     */
    public function verifyCaptcha(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:100',
            'a'      => 'required|integer',
            'b'      => 'required|integer',
            'c'      => 'required|integer',
            'answer' => 'required|integer',
        ]);

        /**
         * =========================================
         * VALIDASI CAPTCHA
         * =========================================
         */
        $correctAnswer =
            (int)$request->a +
            (int)$request->b +
            (int)$request->c;

        if ((int)$request->answer !== $correctAnswer) {

            return response()->json([
                'status' => false,
                'message' => 'Jawaban Salah, Pastikan Anda Manusia'
            ], 422);
        }

        /**
         * =========================================
         * CARI USER
         * =========================================
         */
        $user = User::whereRaw(
            'LOWER(name) = ?',
            [strtolower(trim($request->name))]
        )->first();

        if (!$user) {

            return response()->json([
                'status' => false,
                'message' => 'Nama tidak ditemukan'
            ], 404);
        }

        /**
         * =========================================
         * GENERATE OTP
         * =========================================
         */
        $this->otpService->generateAndSendOtp(
            $user,
            $request,
            'reset_password'
        );

        /**
         * =========================================
         * RESPONSE
         * =========================================
         */
        return response()->json([
            'status' => true,
            'message' => 'Kode OTP berhasil dikirim',
            'email' => $user->email,
            'otpSent' => true,
            'user_id' => $user->id,
        ]);
    }

    /**
     * =========================================
     * STEP 2
     * VERIFY OTP RESET PASSWORD
     * =========================================
     */
    public function verifyOtpResetPassword(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'otp'     => 'required|digits:6',
        ]);

        /**
         * =========================================
         * USER
         * =========================================
         */
        $user = User::findOrFail(
            $request->user_id
        );

        try {

            /**
             * =========================================
             * VERIFY OTP
             * =========================================
             */
            $this->otpService->verifyOtp(
                $user->email,
                $request->otp,
                'reset_password'
            );

            /**
             * =========================================
             * SIMPAN SESSION RESET PASSWORD
             * =========================================
             */
            $request->session()->put(
                'password_reset_verified',
                true
            );

            $request->session()->put(
                'password_reset_user_id',
                $user->id
            );

            $request->session()->put(
                'password_reset_time',
                now()->timestamp
            );

            /**
             * BIND KE IP
             */
            $request->session()->put(
                'password_reset_ip',
                $request->ip()
            );

            /**
             * BIND KE DEVICE/BROWSER
             */
            $request->session()->put(
                'password_reset_agent',
                substr(
                    $request->userAgent(),
                    0,
                    255
                )
            );

            /**
             * PAKSA SIMPAN SESSION
             */
            $request->session()->save();

            /**
             * =========================================
             * SUCCESS
             * =========================================
             */
            return response()->json([
                'status' => true,
                'message' => 'OTP berhasil diverifikasi',
                'redirect' => route(
                    'management.input.password'
                )
            ]);
        } catch (\Throwable $e) {

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 401);
        }
    }

    /**
     * =========================================
     * VALIDASI SESSION RESET PASSWORD
     * =========================================
     */
    private function validateResetSession(
        Request $request
    ): array {

        /**
         * =========================================
         * SESSION ID
         * =========================================
         */
        $sessionId =
            $request->session()->getId();

        /**
         * =========================================
         * CEK SESSION ADA DI DATABASE
         * =========================================
         */
        $sessionExists = DB::table('sessions')
            ->where('id', $sessionId)
            ->exists();

        if (!$sessionExists) {

            return [
                'status' => false,
                'message' => 'Session tidak ditemukan'
            ];
        }

        /**
         * =========================================
         * SESSION RESET HARUS ADA
         * =========================================
         */
        if (
            !$request->session()->get(
                'password_reset_verified'
            )
        ) {

            return [
                'status' => false,
                'message' => 'Session reset password tidak valid'
            ];
        }

        /**
         * =========================================
         * USER ID HARUS ADA
         * =========================================
         */
        if (
            !$request->session()->get(
                'password_reset_user_id'
            )
        ) {

            return [
                'status' => false,
                'message' => 'User reset password tidak valid'
            ];
        }

        /**
         * =========================================
         * VALIDASI IP ADDRESS
         * =========================================
         */
        if (
            $request->session()->get(
                'password_reset_ip'
            ) !== $request->ip()
        ) {

            return [
                'status' => false,
                'message' => 'IP address tidak cocok'
            ];
        }

        /**
         * =========================================
         * VALIDASI USER AGENT
         * =========================================
         */
        if (
            $request->session()->get(
                'password_reset_agent'
            ) !== substr(
                $request->userAgent(),
                0,
                255
            )
        ) {

            return [
                'status' => false,
                'message' => 'Browser/device tidak cocok'
            ];
        }

        /**
         * =========================================
         * VALIDASI EXPIRED
         * =========================================
         */
        $timestamp = $request->session()->get(
            'password_reset_time'
        );

        if (
            !$timestamp ||
            now()->timestamp - $timestamp > 600
        ) {

            /**
             * HAPUS SESSION RESET
             */
            $request->session()->forget([
                'password_reset_verified',
                'password_reset_user_id',
                'password_reset_time',
                'password_reset_ip',
                'password_reset_agent'
            ]);

            return [
                'status' => false,
                'message' => 'Session expired'
            ];
        }

        return [
            'status' => true
        ];
    }

    /**
     * =========================================
     * FORM INPUT PASSWORD BARU
     * =========================================
     */
    public function inputPassword(Request $request)
    {
        /**
         * VALIDASI SESSION RESET
         */
        $validate = $this->validateResetSession(
            $request
        );

        if (!$validate['status']) {

            abort(
                403,
                $validate['message']
            );
        }

        return view(
            'backend.layouts.settingpassword.input_password_baru'
        );
    }

    /**
     * =========================================
     * UPDATE PASSWORD BARU
     * =========================================
     */
    public function updatePassword(Request $request)
    {
        $request->validate([

            'password' => [

                'required',
                'string',
                'min:8',
                'confirmed',

                /**
                 * =========================================
                 * Minimal:
                 * - 1 huruf besar
                 * - 1 angka
                 * =========================================
                 */
                'regex:/^(?=.*[A-Z])(?=.*[0-9]).+$/',

            ]

        ], [

            /**
             * =========================================
             * CUSTOM MESSAGE
             * =========================================
             */
            'password.required' =>
            'Password wajib diisi',

            'password.string' =>
            'Format password tidak valid',

            'password.min' =>
            'Password minimal 8 karakter',

            'password.confirmed' =>
            'Konfirmasi password tidak cocok',

            'password.regex' =>
            'Password harus mengandung minimal 1 huruf besar dan 1 angka',

        ]);

        /**
         * =========================================
         * VALIDASI SESSION RESET
         * =========================================
         */
        $validate = $this->validateResetSession(
            $request
        );

        if (!$validate['status']) {

            return response()->json([
                'status'  => false,
                'message' => $validate['message']
            ], 403);
        }

        /**
         * =========================================
         * USER
         * =========================================
         */
        $userId = $request->session()->get(
            'password_reset_user_id'
        );

        $user = User::findOrFail($userId);

        /**
         * =========================================
         * SESSION SEKARANG
         * =========================================
         */
        $currentSessionId =
            $request->session()->getId();

        /**
         * =========================================
         * UPDATE PASSWORD
         * =========================================
         */
        $user->update([

            'password' => bcrypt(
                $request->password
            )

        ]);

        /**
         * =========================================
         * LOGOUT SEMUA DEVICE
         * KECUALI SESSION SEKARANG
         * =========================================
         */
        DB::table('sessions')
            ->where('user_id', $user->id)
            ->where('id', '!=', $currentSessionId)
            ->delete();

        /**
         * =========================================
         * HAPUS SESSION RESET
         * =========================================
         */
        $request->session()->forget([

            'password_reset_verified',
            'password_reset_user_id',
            'password_reset_time',
            'password_reset_ip',
            'password_reset_agent'

        ]);

        /**
         * =========================================
         * LOGOUT SESSION SEKARANG
         * =========================================
         */
        $request->session()->invalidate();

        $request->session()->regenerateToken();

        /**
         * =========================================
         * SUCCESS
         * =========================================
         */
        return response()->json([

            'status' => true,

            'message' =>
            'Password berhasil diubah. Semua perangkat telah logout.',

            'redirect' => route(
                'showlogin_management'
            )

        ]);
    }
}
