<?php

namespace App\Jobs;

use App\Mail\ApplicationAdminDecisionMail;
use App\Models\LoanApplication;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendApplicationAdminDecisionEmailJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $timeout = 60;

    /**
     * @var array<int, int>
     */
    public array $backoff = [5, 15, 30];

    public function __construct(
        public int $loanApplicationId,
        public string $decision,
        public ?string $message,
    ) {}

    public function handle(): void
    {
        $application = LoanApplication::query()->with('user')->find($this->loanApplicationId);
        $email = $application?->user?->email;
        if (! $application || ! $email) {
            return;
        }

        Mail::to($email)->send(new ApplicationAdminDecisionMail($application, $this->decision, $this->message));
    }
}
