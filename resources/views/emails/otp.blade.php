<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }} — {{ __('Verification') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin:0;background:#0f172a;font-family:system-ui,-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;color:#e2e8f0;">
    <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background:#0f172a;padding:32px 0;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="max-width:520px;background:linear-gradient(145deg,#1e293b,#0b1220);border-radius:24px;padding:32px;border:1px solid rgba(148,163,184,0.2);">
                    <tr>
                        <td align="center" style="padding-bottom:16px;">
                            <div style="display:inline-flex;align-items:center;gap:8px;font-weight:700;color:#fff;font-size:18px;">
                                <span style="display:inline-flex;height:36px;width:36px;align-items:center;justify-content:center;border-radius:12px;background:linear-gradient(135deg,#2563eb,#4f46e5);font-size:14px;">FV</span>
                                {{ config('app.name') }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center;font-size:15px;line-height:1.6;color:#cbd5f5;">
                            {{ __('Your verification code is') }}
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding:24px 0;">
                            <div style="font-size:36px;font-weight:800;letter-spacing:0.35em;color:#fff;">{{ $plainCode }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center;font-size:13px;line-height:1.6;color:#94a3b8;">
                            {{ __('This code expires in :m minutes. If you did not request it, you can ignore this email.', ['m' => 5]) }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
