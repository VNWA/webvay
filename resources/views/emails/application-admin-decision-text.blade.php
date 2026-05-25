@if($decision === 'approved')
{{ __('mail_admin_decision_approved_heading') }}

{{ __('mail_admin_decision_approved_body', ['ref' => $application->reference]) }}
@if($application->access_token)
{{ __('mail_admin_decision_approved_cta') }}: {{ route('apply.ref.result', ['reference' => $application->reference, 't' => $application->access_token]) }}
@endif
@elseif($decision === 'rejected')
{{ __('mail_admin_decision_rejected_heading') }}

{{ __('mail_admin_decision_rejected_body', ['ref' => $application->reference]) }}
@if($message)
{{ $message }}
@endif
@else
{{ __('mail_admin_decision_revision_heading') }}

{{ __('mail_admin_decision_revision_body', ['ref' => $application->reference]) }}
@if($message)
{{ $message }}
@endif
@if($application->access_token)
{{ __('mail_admin_decision_revision_cta') }}: {{ route('apply.ref.personal', ['reference' => $application->reference, 't' => $application->access_token]) }}
@endif
@endif
