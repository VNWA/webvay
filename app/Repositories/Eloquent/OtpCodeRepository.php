<?php

namespace App\Repositories\Eloquent;

use App\Enums\OtpPurpose;
use App\Models\OtpCode;
use App\Repositories\Contracts\OtpCodeRepositoryInterface;

class OtpCodeRepository implements OtpCodeRepositoryInterface
{
    public function latestForEmail(string $email, OtpPurpose $purpose): ?OtpCode
    {
        return OtpCode::query()
            ->where('email', $email)
            ->where('purpose', $purpose)
            ->latest()
            ->first();
    }

    public function create(array $attributes): OtpCode
    {
        return OtpCode::query()->create($attributes);
    }

    public function save(OtpCode $otp): void
    {
        $otp->save();
    }
}
