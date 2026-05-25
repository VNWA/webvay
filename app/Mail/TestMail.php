<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TestMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $recipientEmail,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('mail_test_subject'),
        );
    }

    public function content(): Content
    {
        return new Content(
            html: 'emails.test-mail',
            text: 'emails.test-mail-text',
        );
    }
}
