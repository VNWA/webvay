<?php

namespace App\Services;

use App\Enums\OtpPurpose;
use App\Enums\UserRole;
use App\Jobs\SendOtpEmailJob;
use App\Models\OtpCode;
use App\Models\User;
use App\Repositories\Contracts\OtpCodeRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class OtpService
{
    public const EXPIRY_MINUTES = 5;

    public const RESEND_COOLDOWN_SECONDS = 60;

    public const MAX_VERIFY_ATTEMPTS = 5;

    public function __construct(
        protected OtpCodeRepositoryInterface $otpCodes,
        protected AuditLogService $audit,
    ) {}

    public function send(string $email, OtpPurpose $purpose, ?User $user, ?string $ip): void
    {
        $latest = $this->otpCodes->latestForEmail($email, $purpose);
        if ($latest && $latest->last_sent_at && $latest->last_sent_at->gt(now()->subSeconds(self::RESEND_COOLDOWN_SECONDS))) {
            throw ValidationException::withMessages([
                'email' => __('Please wait before requesting another code.'),
            ]);
        }

        $plain = (string) random_int(100000, 999999);
        $codeHash = Hash::make($plain);

        $otp = $this->otpCodes->create([
            'email' => $email,
            'user_id' => $user?->id,
            'purpose' => $purpose,
            'code_hash' => $codeHash,
            'expires_at' => now()->addMinutes(self::EXPIRY_MINUTES),
            'attempts' => 0,
            'ip_address' => $ip,
            'last_sent_at' => now(),
        ]);

        SendOtpEmailJob::dispatch($email, $plain, $purpose)->onQueue('mail');

        // Job chạy trên queue `mail` — worker phải lắng nghe `default,mail` (xem composer run dev).
        $this->audit->log($user, 'otp.sent', OtpCode::class, $otp->id, [
            'email' => $email,
            'purpose' => $purpose->value,
        ], $ip, null);
    }

    /**
     * @return array{user: User, otp: OtpCode}
     */
    public function verify(string $email, string $code, OtpPurpose $purpose, ?string $ip): array
    {
        $otp = $this->otpCodes->latestForEmail($email, $purpose);

        if (! $otp || $otp->consumed_at) {
            throw ValidationException::withMessages([
                'code' => __('Invalid or expired verification code.'),
            ]);
        }

        if ($otp->expires_at->isPast()) {
            throw ValidationException::withMessages([
                'code' => __('This code has expired. Request a new one.'),
            ]);
        }

        if ($otp->attempts >= self::MAX_VERIFY_ATTEMPTS) {
            throw ValidationException::withMessages([
                'code' => __('Too many failed attempts. Request a new code.'),
            ]);
        }

        if (! Hash::check($code, $otp->code_hash)) {
            $otp->attempts++;
            $this->otpCodes->save($otp);
            $this->audit->log(null, 'otp.verify_failed', OtpCode::class, $otp->id, ['email' => $email], $ip, null);

            throw ValidationException::withMessages([
                'code' => __('The code you entered is incorrect.'),
            ]);
        }

        $otp->consumed_at = now();
        $this->otpCodes->save($otp);

        $user = User::query()->where('email', $email)->first();
        if (! $user) {
            $user = User::query()->create([
                'name' => Str::before($email, '@'),
                'email' => $email,
                'password' => Hash::make(Str::password(32)),
                'role' => UserRole::Customer,
                'email_verified_at' => now(),
            ]);
        }

        $this->audit->log($user, 'otp.verified', OtpCode::class, $otp->id, ['purpose' => $purpose->value], $ip, null);

        return ['user' => $user, 'otp' => $otp];
    }
}
