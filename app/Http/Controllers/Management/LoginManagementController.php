<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\AuthService;
use App\Services\OtpService;
use App\Services\DeviceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LoginManagementController extends Controller
{
    /**
     * 🔥 SERVICES
     */
    protected AuthService $authService;

    protected OtpService $otpService;

    protected DeviceService $deviceService;

    /**
     * 🔥 CONSTRUCTOR
     */
    public function __construct(
        AuthService $authService,
        OtpService $otpService,
        DeviceService $deviceService
    ) {
        $this->authService = $authService;
        $this->otpService = $otpService;
        $this->deviceService = $deviceService;
    }

    /**
     * 🔥 HALAMAN LOGIN
     */
    public function showLogin_management(): View
    {
        return view('backend.management.auth.login_management');
    }

    /**
     * 🔥 STEP 1
     * VALIDASI PASSWORD
     * KIRIM OTP
     */
    public function login_management(
        Request $request
    ): JsonResponse {

        $request->validate([

            'email' => ['required', 'email'],

            'password' => ['required']
        ]);

        /**
         * 🔥 LOGIN PASSWORD
         */
        $user = $this->authService->loginWithPassword(
            $request->email,
            $request->password,
            $request
        );

        /**
         * ✅ RESPONSE
         */
        return response()->json([

            'status'  => true,

            'message' => 'Password valid. OTP berhasil dikirim.',

            'email'   => $user->email,

            'otpSent' => true
        ]);
    }

    /**
     * 🔥 STEP 2
     * VERIFY OTP
     */
    public function verifyOtp(
        Request $request
    ): JsonResponse {

        $request->validate([

            'email' => ['required', 'email'],

            'otp'   => ['required', 'digits:6']
        ]);

        /**
         * 🔥 VERIFY OTP
         */
        $user = $this->otpService->verifyOtp(
            $request->email,
            $request->otp
        );

        /**
         * 🔥 LOGIN SESSION
         */
        Auth::login($user);

        $request->session()->regenerate();

        /**
         * 🔥 REGISTER DEVICE
         */
        $token = $this->deviceService
            ->registerDevice($user, $request);

        /**
         * 🔥 SAVE TOKEN SESSION
         */
        $request->session()->put(
            'login_token',
            $token
        );

        /**
         * ✅ SUCCESS
         */
        return response()->json([

            'status'   => true,

            'message'  => 'Login berhasil',

            'redirect' => route('management.dashboard'),

            'token'    => $token
        ]);
    }

    /**
     * 🔥 LOGOUT
     */
    public function logout_management(
        Request $request
    ): RedirectResponse {

        if (Auth::check()) {

            /** @var \App\Models\User $user */
            $user = Auth::user();

            /**
             * 🔥 HAPUS DEVICE TOKEN
             */
            $this->deviceService
                ->logoutDevice($user->id);

            /**
             * 🔥 RESET VERIFICATION
             */
            $user->forceFill([

                'email_verified_at' => null

            ])->save();
        }

        /**
         * 🔥 LOGOUT
         */
        Auth::logout();

        /**
         * 🔥 CLEAR SESSION
         */
        $request->session()->invalidate();

        $request->session()->regenerateToken();

        /**
         * 🔥 REDIRECT LOGIN
         */
        return redirect()
            ->route('showlogin_management');
    }
}
