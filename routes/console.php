<?php

use App\Mail\TestMail;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('mail:test {email? : Địa chỉ email người nhận}', function (?string $email): int {
    $mailer = (string) config('mail.default');

    $this->info(__('mail_test_using_mailer', ['mailer' => $mailer]));

    if ($mailer === 'resend' && blank(config('mail.mailers.resend.key')) && blank(config('services.resend.key'))) {
        $this->error(__('mail_test_missing_resend_key'));

        return 1;
    }

    $to = $email ?? $this->ask(__('mail_test_prompt_recipient'), config('mail.from.address'));

    if (! is_string($to) || ! filter_var($to, FILTER_VALIDATE_EMAIL)) {
        $this->error(__('mail_test_invalid_email'));

        return 1;
    }

    try {
        Mail::to($to)->send(new TestMail($to));
    } catch (\Throwable $e) {
        $this->error(__('mail_test_send_failed').': '.$e->getMessage());

        return 1;
    }

    $this->components->success(__('mail_test_sent_ok', ['email' => $to]));

    return 0;
})->purpose('Gửi email thử qua mailer hiện tại (Resend khi MAIL_MAILER=resend).');
