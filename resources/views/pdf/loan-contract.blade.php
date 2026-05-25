<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #0f172a; }
        h1 { font-size: 20px; margin-bottom: 8px; }
        .box { border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; margin-top: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        td { padding: 6px 4px; border-bottom: 1px solid #e2e8f0; }
    </style>
</head>
<body>
    <h1>{{ __('Loan Agreement') }}</h1>
    <p><strong>{{ __('Contract code') }}:</strong> {{ $contract->code }}</p>
    <p><strong>{{ __('Application reference') }}:</strong> {{ $application->reference }}</p>
    <div class="box">
        <table>
            <tr><td>{{ __('Borrower') }}</td><td>{{ $application->profile?->full_name }}</td></tr>
            <tr><td>{{ __('Email') }}</td><td>{{ $application->user?->email }}</td></tr>
            <tr><td>{{ __('CCCD') }}</td><td>{{ $application->profile?->cccd_number }}</td></tr>
            <tr><td>{{ __('Principal') }}</td><td>{{ number_format((int) $application->approved_amount) }} ₫</td></tr>
            <tr><td>{{ __('Tenure') }}</td><td>{{ $application->tenure_months }} {{ __('months') }}</td></tr>
            <tr><td>{{ __('Monthly payment (est.)') }}</td><td>{{ number_format((int) $application->monthly_payment) }} ₫</td></tr>
        </table>
    </div>
    <p style="margin-top:16px;">{{ __('By signing electronically you agree to repay according to the amortization schedule provided in-app. This is a demonstration document.') }}</p>
</body>
</html>
