<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendOtpMail extends Mailable
{
    use SerializesModels;

    /*
    |--------------------------------------------------------------------------
    | OTP TYPES
    |--------------------------------------------------------------------------
    */

    public const TYPE_LOGIN = 'login';

    public const TYPE_RESET_PASSWORD = 'reset_password';

    public const TYPE_RESET_FORGOT_PASSWORD = 'reset_forgot_password';

    /*
    |--------------------------------------------------------------------------
    | PROPERTIES
    |--------------------------------------------------------------------------
    */

    public function __construct(
        public string $otp,
        public ?User $user = null,
        public string $type = self::TYPE_LOGIN,
    ) {}

    /*
    |--------------------------------------------------------------------------
    | CONFIG MAP
    |--------------------------------------------------------------------------
    */

    protected function resolveMailConfig(): array
    {
        return match ($this->type) {

            self::TYPE_RESET_PASSWORD => [
                'subject' => 'Kode OTP Reset Password',
                'view'    => 'emails.otp_reset_password',
            ],

            self::TYPE_RESET_FORGOT_PASSWORD => [
                'subject' => 'Kode OTP Forgot Password',
                'view'    => 'emails.otp_forgot_password',
            ],

            default => [
                'subject' => 'Kode OTP Login',
                'view'    => 'emails.otp',
            ],
        };
    }

    /*
    |--------------------------------------------------------------------------
    | ENVELOPE
    |--------------------------------------------------------------------------
    */

    public function envelope(): Envelope
    {
        $config = $this->resolveMailConfig();

        return new Envelope(
            subject: $config['subject'],
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CONTENT
    |--------------------------------------------------------------------------
    */

    public function content(): Content
    {
        $config = $this->resolveMailConfig();

        return new Content(
            view: $config['view'],
            with: [
                'otp'  => $this->otp,
                'user' => $this->user,
                'type' => $this->type,
            ],
        );
    }
}
