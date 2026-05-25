<?php

namespace App\Mail;

use App\Models\LoanApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicationCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public LoanApplication $application,
        public string $signedSummaryUrl,
        public string $lookupPageUrl,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('mail_application_completed_subject'),
        );
    }

    public function content(): Content
    {
        return new Content(
            html: 'emails.application-completed',
            text: 'emails.application-completed-text',
        );
    }
}
