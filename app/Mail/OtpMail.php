<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $code,
        public string $purpose,
    ) {}

    public function envelope(): Envelope
    {
        $subjects = [
            'teacher_signup' => 'ALWEFAQ - Verify Your Email',
            'teacher_login' => 'ALWEFAQ - Login Verification Code',
            'default' => 'ALWEFAQ - Verification Code',
        ];

        return new Envelope(
            subject: $subjects[$this->purpose] ?? $subjects['default'],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.otp',
        );
    }
}
