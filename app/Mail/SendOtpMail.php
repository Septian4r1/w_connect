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
         * 🔥 DEFAULT
         */
        $subject = 'Kode OTP Login';

        $view = 'emails.otp';

        /**
         * 🔥 RESET PASSWORD
         */
        if ($this->type === 'reset_password') {

            $subject = 'Kode OTP Reset Password';

            $view = 'emails.otp_reset_password';
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
