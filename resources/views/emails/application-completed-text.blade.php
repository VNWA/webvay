{{ __('mail_application_completed_heading') }}

{{ __('mail_application_completed_body', ['ref' => $application->reference]) }}

{{ __('mail_application_completed_reference_label') }} {{ $application->reference }}

{{ __('mail_application_completed_cta') }}:
{{ $signedSummaryUrl }}

{{ __('mail_application_completed_lookup_hint') }} {{ $lookupPageUrl }}

{{ __('mail_application_completed_link_expiry') }}
