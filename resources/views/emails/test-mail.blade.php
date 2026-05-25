<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('mail_test_subject') }}</title>
</head>
<body style="margin:0;font-family:system-ui,-apple-system,sans-serif;background:#f8fafc;color:#0f172a;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="padding:24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" style="max-width:480px;background:#fff;border-radius:16px;border:1px solid #e2e8f0;padding:28px;">
                    <tr>
                        <td>
                            <p style="margin:0 0 8px;font-size:12px;font-weight:600;color:#2563eb;text-transform:uppercase;letter-spacing:.06em;">{{ config('app.name') }}</p>
                            <h1 style="margin:0 0 12px;font-size:22px;line-height:1.25;">{{ __('mail_test_heading') }}</h1>
                            <p style="margin:0 0 16px;font-size:15px;line-height:1.6;color:#475569;">{{ __('mail_test_intro', ['email' => e($recipientEmail)]) }}</p>
                            <p style="margin:0 0 8px;font-size:14px;line-height:1.6;color:#64748b;">{{ __('mail_test_mailer', ['mailer' => e(config('mail.default'))]) }}</p>
                            <p style="margin:0;font-size:13px;color:#94a3b8;">{{ __('mail_test_sent_at', ['time' => now()->timezone(config('app.timezone'))->format('d/m/Y H:i:s')]) }}</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
