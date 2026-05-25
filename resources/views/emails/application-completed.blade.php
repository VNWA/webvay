<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('mail_application_completed_subject') }}</title>
</head>
<body style="margin:0;padding:0;background:#f8fafc;font-family:ui-sans-serif,system-ui,sans-serif;">
    <div style="max-width:560px;margin:0 auto;padding:32px 20px;">
        <div style="background:#fff;border-radius:16px;padding:28px 24px;box-shadow:0 4px 24px rgba(15,23,42,.08);">
            <p style="margin:0 0 12px;font-size:14px;color:#64748b;">{{ config('app.name') }}</p>
            <h1 style="margin:0 0 16px;font-size:20px;color:#0f172a;">{{ __('mail_application_completed_heading') }}</h1>
            <p style="margin:0 0 16px;font-size:15px;line-height:1.6;color:#334155;">
                {{ __('mail_application_completed_body', ['ref' => $application->reference]) }}
            </p>
            <p style="margin:0 0 8px;font-size:13px;color:#64748b;">{{ __('mail_application_completed_reference_label') }}</p>
            <p style="margin:0 0 24px;font-size:18px;font-weight:700;font-family:ui-monospace,monospace;color:#0f172a;">{{ $application->reference }}</p>
            <a href="{{ $signedSummaryUrl }}" style="display:inline-block;background:#2563eb;color:#fff;text-decoration:none;padding:14px 24px;border-radius:12px;font-weight:600;font-size:15px;">{{ __('mail_application_completed_cta') }}</a>
            <p style="margin:24px 0 0;font-size:13px;line-height:1.6;color:#64748b;">
                {{ __('mail_application_completed_lookup_hint') }}
                <a href="{{ $lookupPageUrl }}" style="color:#2563eb;">{{ __('mail_application_completed_lookup_link') }}</a>
            </p>
            <p style="margin:20px 0 0;font-size:12px;color:#94a3b8;">{{ __('mail_application_completed_link_expiry') }}</p>
        </div>
    </div>
</body>
</html>
