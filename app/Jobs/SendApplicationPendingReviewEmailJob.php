<?php

namespace App\Jobs;

use App\Mail\ApplicationPendingReviewMail;
use App\Models\LoanApplication;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class SendApplicationPendingReviewEmailJob implements ShouldQueue
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
    ) {}

    public function handle(): void
    {
        $application = LoanApplication::query()->with('user')->find($this->loanApplicationId);
        $email = $application?->user?->email;
        if (! $application || ! $email) {
            return;
        }

        $signedSummaryUrl = URL::temporarySignedRoute(
            'application.public.summary',
            now()->addMonths(3),
            ['application' => $application->id],
        );

        Mail::to($email)->send(new ApplicationPendingReviewMail(
            $application,
            $signedSummaryUrl,
            route('application.lookup.create'),
        ));
    }
}
