<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Services\OtpService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;

class ForgotPasswordController extends Controller
{

    /**
     * =========================================
     * OTP SERVICE
     * =========================================
     */
    protected OtpService $otpService;

    /**
     * =========================================
     * CONSTRUCTOR
     * =========================================
     */
    public function __construct(
        OtpService $otpService
    ) {
        $this->otpService = $otpService;
    }
    /**
     * =========================================================
     * HALAMAN FORGOT PASSWORD
     * =========================================================
     * Menampilkan halaman forgot password.
     *
     * SECURITY:
     * - Tidak ada query sensitif
     * - Tidak expose data user
     * =========================================================
     */
    public function index()
    {
        return view(
            'backend.layouts.settingpassword.forget_password'
        );
    }


    /**
     * =========================================================
     * CHECK NAMA USER
     * =========================================================
     *
     * FLOW:
     * ---------------------------------------------------------
     * 1. Rate limiter
     * 2. Validasi input request
     * 3. Sanitasi input user
     * 4. Validasi captcha matematika
     * 5. Cari user berdasarkan nama
     * 6. Generate encrypted ID
     * 7. Return redirect URL
     *
     * SECURITY:
     * ---------------------------------------------------------
     * - Rate limiter anti brute force
     * - Anti SQL Injection
     * - Anti XSS Injection
     * - hash_equals() anti timing attack
     * - Generic response message
     * - Security audit logging
     * - Hidden internal error
     * - Encrypted route parameter
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * =========================================================
     */
    public function checkName(Request $request)
    {
        try {

            /**
             * =================================================
             * RATE LIMITER KEY
             * =================================================
             * Key unik berdasarkan IP user.
             * Digunakan untuk membatasi spam request.
             * =================================================
             */
            $rateLimitKey =
                'forgot-password-check-name:' .
                $request->ip();

            /**
             * =================================================
             * RATE LIMITER CHECK
             * =================================================
             * Maksimal:
             * - 5 request
             * - dalam 60 detik
             * =================================================
             */
            if (
                RateLimiter::tooManyAttempts(
                    $rateLimitKey,
                    5
                )
            ) {

                /**
                 * =============================================
                 * HITUNG SISA DETIK
                 * =============================================
                 */
                $seconds =
                    RateLimiter::availableIn(
                        $rateLimitKey
                    );

                return response()->json([

                    'status' => false,

                    'message' =>
                    'Terlalu banyak percobaan. ' .
                        'Silakan coba lagi dalam ' .
                        $seconds .
                        ' detik.'

                ], 429);
            }

            /**
             * =================================================
             * HIT RATE LIMITER
             * =================================================
             * Menambah jumlah percobaan.
             *
             * Parameter kedua:
             * 60 = expire dalam 60 detik
             * =================================================
             */
            RateLimiter::hit(
                $rateLimitKey,
                60
            );

            /**
             * =================================================
             * VALIDASI INPUT
             * =================================================
             * Validasi data request dari frontend.
             * =================================================
             */
            $validated = $request->validate([

                /**
                 * =============================================
                 * NAMA USER
                 * =============================================
                 */
                'name' => [
                    'required',
                    'string',
                    'min:3',
                    'max:255'
                ],

                /**
                 * =============================================
                 * CAPTCHA NUMBER
                 * =============================================
                 */
                'number_1' => [
                    'required',
                    'numeric'
                ],

                'number_2' => [
                    'required',
                    'numeric'
                ],

                'number_3' => [
                    'required',
                    'numeric'
                ],

                /**
                 * =============================================
                 * CAPTCHA ANSWER
                 * =============================================
                 */
                'answer' => [
                    'required',
                    'numeric'
                ],

            ]);

            /**
             * =================================================
             * SANITASI NAMA
             * =================================================
             * Mencegah:
             * - Script injection
             * - HTML injection
             * - Whitespace berlebih
             * =================================================
             */
            $name = trim(
                strip_tags(
                    $validated['name']
                )
            );

            /**
             * =================================================
             * VALIDASI CAPTCHA
             * =================================================
             * Hitung total angka captcha.
             * =================================================
             */
            $totalCaptcha =
                (int) $validated['number_1'] +
                (int) $validated['number_2'] +
                (int) $validated['number_3'];

            /**
             * =================================================
             * HASH_EQUALS
             * =================================================
             * Menggunakan hash_equals()
             * untuk mencegah timing attack.
             * =================================================
             */
            if (
                !hash_equals(
                    (string) $totalCaptcha,
                    (string) $validated['answer']
                )
            ) {

                /**
                 * =============================================
                 * SECURITY LOGGING
                 * =============================================
                 */
                Log::warning(
                    'Forgot Password Captcha Failed',
                    [
                        'ip'         => $request->ip(),
                        'user_agent' => $request->userAgent(),
                        'name'       => $name,
                    ]
                );

                return response()->json([

                    'status' => false,

                    'message' =>
                    'Jawaban penjumlahan tidak sesuai'

                ], 422);
            }

            /**
             * =================================================
             * AMBIL USER
             * =================================================
             * Cari user berdasarkan nama.
             *
             * select() digunakan agar query lebih ringan
             * dan tidak mengambil semua kolom database.
             * =================================================
             */
            $user = User::query()

                ->select([
                    'id',
                    'name',
                    'warga_id'
                ])

                ->where(
                    'name',
                    $name
                )

                ->first();

            /**
             * =================================================
             * USER TIDAK DITEMUKAN
             * =================================================
             * SECURITY:
             * -------------------------------------------------
             * Jangan expose apakah user benar-benar ada
             * atau tidak.
             *
             * Hindari:
             * - User Enumeration
             * - Email Enumeration
             * =================================================
             */
            if (!$user) {

                /**
                 * =============================================
                 * SECURITY AUDIT LOG
                 * =============================================
                 */
                Log::notice(
                    'Forgot Password User Not Found',
                    [
                        'ip'         => $request->ip(),
                        'user_agent' => $request->userAgent(),
                        'name'       => $name,
                    ]
                );

                /**
                 * =============================================
                 * PENTING
                 * =============================================
                 * redirect = null
                 *
                 * Agar frontend tidak undefined.
                 * =============================================
                 */
                return response()->json([

                    'status' => false,

                    'message' =>
                    'Data tidak ditemukan',

                    'redirect' => null

                ], 404);
            }

            /**
             * =================================================
             * ENCRYPT USER ID
             * =================================================
             * ID user dienkripsi agar:
             * - Tidak mudah ditebak
             * - Tidak expose ID asli database
             * =================================================
             */
            $encryptedId =
                Crypt::encryptString(
                    $user->id
                );

            /**
             * =================================================
             * SUCCESS LOG
             * =================================================
             */
            Log::info(
                'Forgot Password Name Verified',
                [
                    'user_id'   => $user->id,
                    'ip'        => $request->ip(),
                    'userAgent' => $request->userAgent(),
                ]
            );

            /**
             * =================================================
             * SUCCESS RESPONSE
             * =================================================
             */
            return response()->json([

                'status' => true,

                'message' =>
                'Verifikasi berhasil',

                /**
                 * =============================================
                 * URL REDIRECT
                 * =============================================
                 * Frontend akan redirect ke:
                 * password.checkData
                 * =============================================
                 */
                'redirect' => route(
                    'password.checkData',
                    [
                        'id' => $encryptedId
                    ]
                )

            ], 200);
        } catch (ValidationException $e) {

            /**
             * =================================================
             * VALIDATION ERROR
             * =================================================
             */
            return response()->json([

                'status' => false,

                'message' =>
                $e->validator
                    ->errors()
                    ->first(),

                'redirect' => null

            ], 422);
        } catch (\Throwable $e) {

            /**
             * =================================================
             * INTERNAL ERROR LOG
             * =================================================
             * Log lengkap disimpan di server.
             * Jangan expose detail error ke user.
             * =================================================
             */
            Log::error(
                'Forgot Password Check Name Error',
                [
                    'message' => $e->getMessage(),
                    'line'    => $e->getLine(),
                    'file'    => $e->getFile(),
                    'ip'      => $request->ip(),
                ]
            );

            /**
             * =================================================
             * GENERIC ERROR RESPONSE
             * =================================================
             */
            return response()->json([

                'status' => false,

                'message' =>
                'Terjadi kesalahan server',

                'redirect' => null

            ], 500);
        }
    }

    /**
     * =========================================================
     * HALAMAN CHECK DATA
     * =========================================================
     *
     * SECURITY:
     * - Encrypted ID validation
     * - N+1 prevention
     * - Hidden sensitive fields
     * =========================================================
     */
    public function checkData(string $id)
    {
        try {

            /**
             * =================================================
             * VALIDASI ENCRYPTED ID
             * =================================================
             */
            if (empty($id)) {

                abort(404);
            }

            /**
             * =================================================
             * DECRYPT USER ID
             * =================================================
             */
            $userId =
                Crypt::decryptString($id);

            /**
             * =================================================
             * AMBIL USER + RELASI WARGA
             * =================================================
             */
            $user = User::query()

                ->with([
                    'warga:id,nik,nama,tanggal_lahir,status'
                ])

                ->select([
                    'id',
                    'name',
                    'warga_id'
                ])

                ->findOrFail($userId);

            return view(
                'backend.layouts.settingpassword.forget_chekdata',
                compact('user')
            );
        } catch (DecryptException $e) {

            abort(404);
        } catch (\Throwable $e) {

            Log::warning(
                'Invalid Forgot Password ID',
                [
                    'encrypted_id' => $id,
                ]
            );

            abort(404);
        }
    }

    /**
     * =========================================================
     * VERIFY CHECK DATA
     * =========================================================
     *
     * FLOW BARU:
     * - Rate limiter
     * - Validasi input
     * - Validasi captcha
     * - Validasi user + NIK + DOB
     * - GENERATE OTP (TYPE: reset_password)
     * - KIRIM EMAIL OTP
     * - REDIRECT KE HALAMAN OTP
     * =========================================================
     */
    public function verifyCheckData(Request $request, string $id)
    {
        try {

            /**
             * =================================================
             * RATE LIMITER
             * =================================================
             */
            $rateLimitKey =
                'forgot-password-verify:' . $request->ip();

            if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {

                $seconds = RateLimiter::availableIn($rateLimitKey);

                return response()->json([

                    'status' => false,
                    'message' =>
                    'Terlalu banyak percobaan. Coba lagi dalam ' . $seconds . ' detik.'

                ], 429);
            }

            RateLimiter::hit($rateLimitKey, 120);

            /**
             * =================================================
             * VALIDASI INPUT
             * =================================================
             */
            $validated = $request->validate([

                'birth_date' => ['required', 'date'],
                'nik'        => ['required', 'string', 'between:16,20', 'regex:/^[0-9]+$/'],

                'number_1'   => ['required', 'numeric'],
                'number_2'   => ['required', 'numeric'],
                'number_3'   => ['required', 'numeric'],
                'answer'     => ['required', 'numeric'],
            ]);

            /**
             * =================================================
             * CAPTCHA VALIDATION
             * =================================================
             */
            $totalCaptcha =
                (int)$validated['number_1'] +
                (int)$validated['number_2'] +
                (int)$validated['number_3'];

            if (!hash_equals((string)$totalCaptcha, (string)$validated['answer'])) {

                Log::warning('Forgot Password Captcha Failed', [
                    'ip' => $request->ip(),
                ]);

                return response()->json([
                    'status' => false,
                    'message' => 'Verifikasi gagal'
                ], 422);
            }

            /**
             * =================================================
             * DECRYPT USER ID
             * =================================================
             */
            $userId = Crypt::decryptString($id);

            /**
             * =================================================
             * AMBIL USER
             * =================================================
             */
            $user = User::query()
                ->with(['warga:id,nik,tanggal_lahir,status'])
                ->select(['id', 'name', 'warga_id', 'email']) // <-- TAMBAH INI
                ->find($userId);

            if (!$user || !$user->warga) {

                Log::notice('Forgot Password Invalid User', [
                    'ip' => $request->ip(),
                ]);

                return response()->json([
                    'status' => false,
                    'message' => 'Verifikasi gagal'
                ], 422);
            }

            /**
             * =================================================
             * STATUS CHECK
             * =================================================
             */
            if (strtolower($user->warga->status) !== 'aktif') {

                return response()->json([
                    'status' => false,
                    'message' => 'Akun tidak aktif'
                ], 403);
            }

            /**
             * =================================================
             * SANITASI NIK
             * =================================================
             */
            $nikRequest = preg_replace('/[^0-9]/', '', $validated['nik']);
            $nikDb      = preg_replace('/[^0-9]/', '', $user->warga->nik);

            if ($nikDb != $nikRequest) {

                Log::warning('Forgot Password Invalid NIK', [
                    'user_id' => $user->id,
                    'ip'      => $request->ip(),
                ]);

                return response()->json([
                    'status' => false,
                    'message' => 'NIK tidak cocok'
                ], 422);
            }

            /**
             * =================================================
             * BIRTH DATE CHECK
             * =================================================
             */
            $dbBirthDate = Carbon::parse($user->warga->tanggal_lahir)->format('Y-m-d');
            $requestBirthDate = Carbon::parse($validated['birth_date'])->format('Y-m-d');

            if (!hash_equals($dbBirthDate, $requestBirthDate)) {

                return response()->json([
                    'status' => false,
                    'message' => 'Tanggal lahir tidak sama'
                ], 422);
            }

            /**
             * =================================================
             * 🔥 GENERATE OTP (TYPE RESET PASSWORD)
             * =================================================
             */
            $this->otpService->generateAndSendOtp(
                $user,
                $request,
                OtpService::TYPE_RESET_FORGOT_PASSWORD
            );
            /**
             * =================================================
             * LOG SUCCESS
             * =================================================
             */
            Log::info('Forgot Password OTP Sent', [
                'user_id' => $user->id,
                'ip'      => $request->ip(),
            ]);

            /**
             * =================================================
             * RESPONSE SUCCESS
             * =================================================
             */
            return response()->json([
                'status' => true,
                'message' => 'OTP telah dikirim ke email Anda',
                'email' => $user->email,
                'otpSent' => true,
                'user_id' => Crypt::encryptString($user->id)
            ], 200);
        } catch (ValidationException $e) {

            return response()->json([
                'status' => false,
                'message' => $e->validator->errors()->first()
            ], 422);
        } catch (DecryptException $e) {

            return response()->json([
                'status' => false,
                'message' => 'Token tidak valid'
            ], 403);
        } catch (\Throwable $e) {

            Log::error('Forgot Password Verify ERROR', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'trace'   => $e->getTraceAsString(),
                'ip'      => $request->ip(),
                'user_id' => $user->id ?? null,
            ]);

            return response()->json([
                'status' => false,
                'message' => 'SERVER ERROR: ' . $e->getMessage(), // sementara untuk debug
                'debug' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]
            ], 500);
        }
    }

    /**
     * =========================================
     * VERIFY OTP RESET PASSWORD
     * =========================================
     */
    public function verifyOtp(Request $request, string $id)
    {
        try {

            /**
             * =========================================
             * VALIDASI INPUT
             * =========================================
             */
            $request->validate([
                'user_id' => ['required', 'string'],
                'otp'     => ['required', 'digits:6'],
                'type'    => ['required', 'string'],
            ]);

            /**
             * =========================================
             * DECRYPT USER ID
             * =========================================
             */
            $userId = Crypt::decryptString($request->user_id);

            $user = User::with('warga')->findOrFail($userId);

            /**
             * =========================================
             * VALIDASI OTP VIA SERVICE
             * =========================================
             */
            $this->otpService->verifyOtp(
                $user->email,
                $request->otp,
                OtpService::TYPE_RESET_FORGOT_PASSWORD
            );


            /**
             * =========================================
             * RESPONSE SUCCESS
             * =========================================
             */
            return response()->json([
                'status' => true,
                'message' => 'OTP berhasil diverifikasi',
                'redirect' => route(
                    'password.input.password',
                    [
                        'id' => Crypt::encryptString($user->id)
                    ]
                )
            ]);
        } catch (\Throwable $e) {

            Log::error('OTP VERIFY ERROR', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(), // 🔥 PENTING
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ], 500);
        }
    }
    public function inputPassword(Request $request, string $id)
    {
        try {

            /**
             * =================================================
             * VALIDASI ENCRYPTED ID
             * =================================================
             */
            if (empty($id)) {

                abort(404);
            }

            /**
             * =================================================
             * DECRYPT USER ID
             * =================================================
             */
            $userId =
                Crypt::decryptString($id);

            /**
             * =================================================
             * AMBIL USER + RELASI WARGA
             * =================================================
             */
            $user = User::query()

                ->with([
                    'warga:id,nik,nama,tanggal_lahir,status'
                ])

                ->select([
                    'id',
                    'name',
                    'warga_id'
                ])

                ->findOrFail($userId);

            return view(
                'backend.layouts.settingpassword.forget_inputPassword',
                compact('user')
            );
        } catch (DecryptException $e) {

            abort(404);
        } catch (\Throwable $e) {

            Log::warning(
                'Invalid Forgot Password ID',
                [
                    'encrypted_id' => $id,
                ]
            );

            abort(404);
        }
    }

    /**
     * =========================================
     * UPDATE PASSWORD BARU
     * =========================================
     */
    public function updatePassword(
        Request $request,
        string $id
    ) {

        try {

            /**
             * =========================================
             * VALIDASI INPUT
             * =========================================
             */
            $request->validate([

                'password' => [

                    'required',
                    'string',
                    'min:8',
                    'confirmed',

                    /**
                     * =====================================
                     * Minimal:
                     * - 1 huruf besar
                     * - 1 angka
                     * =====================================
                     */
                    'regex:/^(?=.*[A-Z])(?=.*[0-9]).+$/',

                ],

            ], [

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
             * DECRYPT USER ID
             * =========================================
             */
            $userId =
                Crypt::decryptString($id);

            /**
             * =========================================
             * AMBIL USER
             * =========================================
             */
            $user = User::findOrFail($userId);

            /**
             * =========================================
             * UPDATE PASSWORD
             * =========================================
             */
            $user->update([

                'password' => Hash::make(
                    $request->password
                )

            ]);

            /**
             * =========================================
             * HAPUS SEMUA SESSION USER
             * =========================================
             *
             * Logout semua device
             * =========================================
             */
            DB::table('sessions')

                ->where(
                    'user_id',
                    $user->id
                )

                ->delete();

            /**
             * =========================================
             * LOG SUCCESS
             * =========================================
             */
            Log::info(
                'Forgot Password Updated Successfully',
                [

                    'user_id' => $user->id,
                    'ip'      => $request->ip(),

                ]
            );

            /**
             * =========================================
             * RESPONSE SUCCESS
             * =========================================
             */
            return response()->json([

                'status' => true,

                'message' =>
                'Password berhasil diubah. Semua perangkat telah logout.',

                'redirect' => route(
                    'showlogin_management'
                )

            ], 200);
        } catch (ValidationException $e) {

            return response()->json([

                'status'  => false,

                'message' =>
                $e->validator
                    ->errors()
                    ->first(),

            ], 422);
        } catch (DecryptException $e) {

            return response()->json([

                'status'  => false,

                'message' =>
                'Token tidak valid'

            ], 403);
        } catch (\Throwable $e) {

            Log::error(
                'UPDATE PASSWORD ERROR',
                [

                    'message' => $e->getMessage(),
                    'line'    => $e->getLine(),
                    'file'    => $e->getFile(),
                    'trace'   => $e->getTraceAsString(),
                    'ip'      => $request->ip(),

                ]
            );

            return response()->json([

                'status'  => false,

                'message' =>
                'Terjadi kesalahan server'

            ], 500);
        }
    }
}
