<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendOtpMail extends Mailable
{
    use SerializesModels;

    public $otp;
    public $user;

    /**
     * @param string $otp Kode OTP
     * @param object|null $user Optional, untuk nama/email user
     */
    public function __construct(string $otp, $user = null)
    {
        $this->otp = $otp;
        $this->user = $user;
    }

    /**
     * Build email
     */
    public function build()
    {
        return $this->subject('Kode OTP Login')
                    ->view('emails.otp')
                    ->with([
                        'otp' => $this->otp,
                        'user' => $this->user,
                    ]);
    }
}
