<?php

namespace App\Mail;

use App\Enums\OtpPurpose;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $plainCode,
        public OtpPurpose $purpose,
    ) {}

    public function envelope(): Envelope
    {
        $subject = $this->purpose === OtpPurpose::ContractSigning
            ? __('Sign your loan agreement')
            : __('Your verification code');

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            html: 'emails.otp',
            text: 'emails.otp-text',
        );
    }
}
