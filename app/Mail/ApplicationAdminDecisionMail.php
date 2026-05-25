<?php

namespace App\Mail;

use App\Models\LoanApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicationAdminDecisionMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public LoanApplication $application,
        public string $decision,
        public ?string $message,
    ) {}

    public function envelope(): Envelope
    {
        $subject = match ($this->decision) {
            'approved' => __('mail_admin_decision_approved_subject'),
            'rejected' => __('mail_admin_decision_rejected_subject'),
            'revision' => __('mail_admin_decision_revision_subject'),
            default => __('mail_admin_decision_subject'),
        };

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            html: 'emails.application-admin-decision',
            text: 'emails.application-admin-decision-text',
        );
    }
}
