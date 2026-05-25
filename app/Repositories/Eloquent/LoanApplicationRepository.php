<?php

namespace App\Repositories\Eloquent;

use App\Enums\LoanApplicationStatus;
use App\Enums\WizardStep;
use App\Models\LoanApplication;
use App\Models\User;
use App\Repositories\Contracts\LoanApplicationRepositoryInterface;
use Illuminate\Support\Str;

class LoanApplicationRepository implements LoanApplicationRepositoryInterface
{
    public function findActiveForUser(User $user): ?LoanApplication
    {
        return LoanApplication::query()
            ->where('user_id', $user->id)
            ->whereNotIn('status', [
                LoanApplicationStatus::Completed,
                LoanApplicationStatus::Rejected,
            ])
            ->latest()
            ->first();
    }

    public function createDraft(User $user, int $desiredAmount): LoanApplication
    {
        $application = new LoanApplication([
            'user_id' => $user->id,
            'reference' => 'LA-'.strtoupper(Str::random(10)),
            'access_token' => Str::random(64),
            'desired_amount' => $desiredAmount,
            'status' => LoanApplicationStatus::InProgress,
            'wizard_step' => WizardStep::Personal,
            'tenure_months' => 12,
        ]);
        $application->save();

        $application->profile()->create([]);
        $application->ekyc()->create([]);

        return $application;
    }

    public function save(LoanApplication $application): void
    {
        $application->save();
    }
}
