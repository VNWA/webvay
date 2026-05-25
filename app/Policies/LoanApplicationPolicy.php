<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\LoanApplication;
use App\Models\User;

class LoanApplicationPolicy
{
    private function isAdmin(User $user): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function viewAny(User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function view(User $user, LoanApplication $loanApplication): bool
    {
        return $this->isAdmin($user) || $user->id === $loanApplication->user_id;
    }

    public function create(User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function update(User $user, LoanApplication $loanApplication): bool
    {
        return $this->isAdmin($user) || $user->id === $loanApplication->user_id;
    }

    public function delete(User $user, LoanApplication $loanApplication): bool
    {
        return $this->isAdmin($user);
    }

    public function deleteAny(User $user): bool
    {
        return $this->isAdmin($user);
    }
}
