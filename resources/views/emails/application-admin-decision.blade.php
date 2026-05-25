<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
</head>
<body style="margin:0;padding:0;background:#f8fafc;font-family:ui-sans-serif,system-ui,sans-serif;">
    <div style="max-width:560px;margin:0 auto;padding:32px 20px;">
        <div style="background:#fff;border-radius:16px;padding:28px 24px;">
            @if($decision === 'approved')
                <h1 style="margin:0 0 12px;font-size:18px;color:#0f172a;">{{ __('mail_admin_decision_approved_heading') }}</h1>
                <p style="margin:0 0 16px;font-size:15px;line-height:1.6;color:#334155;">{{ __('mail_admin_decision_approved_body', ['ref' => $application->reference]) }}</p>
                @if($application->access_token)
                    <a href="{{ route('apply.ref.result', ['reference' => $application->reference, 't' => $application->access_token]) }}" style="display:inline-block;background:#059669;color:#fff;text-decoration:none;padding:14px 24px;border-radius:12px;font-weight:600;">{{ __('mail_admin_decision_approved_cta') }}</a>
                @endif
            @elseif($decision === 'rejected')
                <h1 style="margin:0 0 12px;font-size:18px;color:#9f1239;">{{ __('mail_admin_decision_rejected_heading') }}</h1>
                <p style="margin:0 0 16px;font-size:15px;line-height:1.6;color:#334155;">{{ __('mail_admin_decision_rejected_body', ['ref' => $application->reference]) }}</p>
                @if($message)
                    <p style="margin:0 0 16px;padding:12px;background:#fff1f2;border-radius:8px;font-size:14px;color:#881337;">{{ $message }}</p>
                @endif
            @else
                <h1 style="margin:0 0 12px;font-size:18px;color:#92400e;">{{ __('mail_admin_decision_revision_heading') }}</h1>
                <p style="margin:0 0 16px;font-size:15px;line-height:1.6;color:#334155;">{{ __('mail_admin_decision_revision_body', ['ref' => $application->reference]) }}</p>
                @if($message)
                    <p style="margin:0 0 16px;padding:12px;background:#fffbeb;border-radius:8px;font-size:14px;color:#78350f;">{{ $message }}</p>
                @endif
                @if($application->access_token)
                    <a href="{{ route('apply.ref.personal', ['reference' => $application->reference, 't' => $application->access_token]) }}" style="display:inline-block;background:#2563eb;color:#fff;text-decoration:none;padding:14px 24px;border-radius:12px;font-weight:600;">{{ __('mail_admin_decision_revision_cta') }}</a>
                @endif
            @endif
        </div>
    </div>
</body>
</html>
