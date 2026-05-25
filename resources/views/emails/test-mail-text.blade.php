{{ __('mail_test_subject') }}

{{ __('mail_test_heading') }}

{{ __('mail_test_intro', ['email' => $recipientEmail]) }}

{{ __('mail_test_mailer', ['mailer' => config('mail.default')]) }}
{{ __('mail_test_sent_at', ['time' => now()->timezone(config('app.timezone'))->format('d/m/Y H:i:s')]) }}
