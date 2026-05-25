{{ __('mail_pending_review_heading') }}

{{ __('mail_pending_review_body', ['ref' => $application->reference]) }}

{{ __('mail_application_completed_reference_label') }} {{ $application->reference }}

{{ __('mail_pending_review_status_line') }}

{{ __('mail_pending_review_cta_lookup') }}:
{{ $lookupPageUrl }}

{{ __('mail_pending_review_quick_view') }} {{ $signedSummaryUrl }}

{{ __('mail_application_completed_link_expiry') }}
