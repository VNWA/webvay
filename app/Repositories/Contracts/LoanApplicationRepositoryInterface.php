<?php

namespace App\Repositories\Contracts;

use App\Models\LoanApplication;
use App\Models\User;

interface LoanApplicationRepositoryInterface
{
    public function findActiveForUser(User $user): ?LoanApplication;

    public function createDraft(User $user, int $desiredAmount): LoanApplication;

    public function save(LoanApplication $application): void;
}
