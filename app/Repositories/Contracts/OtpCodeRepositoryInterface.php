<?php

namespace App\Repositories\Contracts;

use App\Enums\OtpPurpose;
use App\Models\OtpCode;

interface OtpCodeRepositoryInterface
{
    public function latestForEmail(string $email, OtpPurpose $purpose): ?OtpCode;

    public function create(array $attributes): OtpCode;

    public function save(OtpCode $otp): void;
}
