<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendOtpMail extends Mailable
{
    use SerializesModels;

    /**
     * 🔥 PUBLIC PROPERTIES
     */
    public string $otp;

    public ?User $user;

    public string $type;

    /**
     * 🔥 CONSTRUCTOR
     */
    public function __construct(
        string $otp,
        ?User $user = null,
        ?string $type = null
    ) {

        $this->otp = $otp;

        $this->user = $user;

        $this->type = $type ?? 'login';
    }

    /**
     * 🔥 BUILD EMAIL
     */
    public function build()
    {
        /**
         * DEFAULT VALUE (LOGIN OTP)
         */
        $subject = 'Kode OTP Login';
        $view    = 'emails.otp';

        /**
         * =========================================
         * SWITCH OTP TYPE
         * =========================================
         */
        switch ($this->type) {

            /**
             * RESET PASSWORD
             */
            case 'reset_password':
                $subject = 'Kode OTP Reset Password';
                $view    = 'emails.otp_reset_password';
                break;

            /**
             * FORGOT PASSWORD
             */
            case 'reset_forgot_password':
                $subject = 'Kode OTP Forgot Password';
                $view    = 'emails.otp_forgot_password';
                break;

            /**
             * LOGIN (DEFAULT)
             */
            default:
                $subject = 'Kode OTP Login';
                $view    = 'emails.otp';
                break;
        }

        return $this
            ->subject($subject)
            ->view($view)
            ->with([
                'otp'  => $this->otp,
                'user' => $this->user,
                'type' => $this->type,
            ]);
    }
}
