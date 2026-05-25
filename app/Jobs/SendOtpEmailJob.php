<?php

namespace App\Jobs;

use App\Enums\OtpPurpose;
use App\Mail\OtpMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendOtpEmailJob implements ShouldQueue
{
    use Queueable;

    /** Số lần thử khi Resend / mạng lỗi tạm thời. */
    public int $tries = 3;

    /** Giây — gửi mail qua API không nên treo quá lâu. */
    public int $timeout = 60;

    /**
     * @var array<int, int>
     */
    public array $backoff = [5, 15, 30];

    public function __construct(
        public string $email,
        public string $plainCode,
        public OtpPurpose $purpose,
    ) {}

    public function handle(): void
    {
        Mail::to($this->email)->send(new OtpMail($this->plainCode, $this->purpose));
    }
}
