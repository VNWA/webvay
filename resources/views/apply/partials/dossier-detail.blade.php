@props(['application', 'publicImageUrls' => null])
@php
    $p = $application->profile;
    $e = $application->ekyc;
    $u = $application->user;
    $lookup = $publicImageUrls === null;
@endphp
<div class="space-y-8 text-left text-sm text-slate-700">
    <section>
        <h3 class="text-xs font-bold uppercase tracking-wide text-slate-500">{{ __('lookup_section_reference') }}</h3>
        <dl class="mt-3 grid gap-3 sm:grid-cols-2">
            <div><dt class="text-slate-500">{{ __('Application reference') }}</dt><dd class="font-mono font-semibold text-slate-900">{{ $application->reference }}</dd></div>
            <div><dt class="text-slate-500">{{ __('field_status') }}</dt><dd class="font-semibold text-slate-900">{{ $application->status->label() }}</dd></div>
            <div><dt class="text-slate-500">{{ __('field_wizard_step') }}</dt><dd class="font-semibold text-slate-900">{{ $application->wizard_step->label() }}</dd></div>
            <div><dt class="text-slate-500">{{ __('field_desired_amount') }}</dt><dd class="font-semibold text-slate-900">{{ number_format((int) $application->desired_amount) }} ₫</dd></div>
            @if($application->revision_message)
                <div class="sm:col-span-2 rounded-xl border border-amber-200 bg-amber-50 p-4">
                    <dt class="font-semibold text-amber-900">{{ __('revision_message_label') }}</dt>
                    <dd class="mt-1 text-amber-950">{{ $application->revision_message }}</dd>
                </div>
            @endif
            @if($application->admin_notes && $application->status->value === 'rejected')
                <div class="sm:col-span-2 rounded-xl border border-rose-200 bg-rose-50 p-4">
                    <dt class="font-semibold text-rose-900">{{ __('admin_rejection_reason') }}</dt>
                    <dd class="mt-1 text-rose-950">{{ $application->admin_notes }}</dd>
                </div>
            @endif
        </dl>
    </section>

    <section>
        <h3 class="text-xs font-bold uppercase tracking-wide text-slate-500">{{ __('lookup_section_account') }}</h3>
        <dl class="mt-3 grid gap-3 sm:grid-cols-2">
            <div><dt class="text-slate-500">{{ __('Email') }}</dt><dd class="font-semibold text-slate-900">{{ $u?->email ?? '—' }}</dd></div>
            <div><dt class="text-slate-500">{{ __('Phone number') }}</dt><dd class="font-semibold text-slate-900">{{ $u?->phone ?? $p?->phone ?? '—' }}</dd></div>
            <div><dt class="text-slate-500">{{ __('Full name') }}</dt><dd class="font-semibold text-slate-900">{{ $p?->full_name ?? '—' }}</dd></div>
        </dl>
    </section>

    @if($p && ($p->full_name || $p->cccd_number))
        <section>
            <h3 class="text-xs font-bold uppercase tracking-wide text-slate-500">{{ __('Personal information') }}</h3>
            <dl class="mt-3 grid gap-3 sm:grid-cols-2">
                <div><dt class="text-slate-500">{{ __('Birthday') }}</dt><dd>{{ $p->birthday?->format('d/m/Y') ?? '—' }}</dd></div>
                <div><dt class="text-slate-500">{{ __('Gender') }}</dt><dd>@if($p->gender){{ match($p->gender) { 'male' => __('Male'), 'female' => __('Female'), default => __('Other') } }}@else — @endif</dd></div>
                <div><dt class="text-slate-500">{{ __('CCCD number') }}</dt><dd>{{ $p->cccd_number ?? '—' }}</dd></div>
                <div class="sm:col-span-2"><dt class="text-slate-500">{{ __('Address') }}</dt><dd>{{ $p->address ?? '—' }}</dd></div>
                <div><dt class="text-slate-500">{{ __('Province') }}</dt><dd>{{ $p->province ?? '—' }}</dd></div>
                <div><dt class="text-slate-500">{{ __('District') }}</dt><dd>{{ $p->district ?? '—' }}</dd></div>
                <div><dt class="text-slate-500">{{ __('Ward') }}</dt><dd>{{ $p->ward ?? '—' }}</dd></div>
            </dl>
        </section>
    @endif

    @if($p && ($p->company || $p->bank_name))
        <section>
            <h3 class="text-xs font-bold uppercase tracking-wide text-slate-500">{{ __('Employment & banking') }}</h3>
            <dl class="mt-3 grid gap-3 sm:grid-cols-2">
                <div><dt class="text-slate-500">{{ __('Company') }}</dt><dd>{{ $p->company ?? '—' }}</dd></div>
                <div><dt class="text-slate-500">{{ __('Job title') }}</dt><dd>{{ $p->job_title ?? '—' }}</dd></div>
                <div><dt class="text-slate-500">{{ __('Monthly income (VND)') }}</dt><dd>{{ $p->monthly_income ? number_format((int) $p->monthly_income) : '—' }}</dd></div>
                <div><dt class="text-slate-500">{{ __('Bank name') }}</dt><dd>{{ $p->bank_name ?? '—' }}</dd></div>
                <div class="sm:col-span-2"><dt class="text-slate-500">{{ __('Bank account') }}</dt><dd class="font-mono">{{ $p->bank_account ?? '—' }}</dd></div>
            </dl>
        </section>
    @endif

    @if((int) $application->approved_amount > 0 || $application->score)
        <section>
            <h3 class="text-xs font-bold uppercase tracking-wide text-slate-500">{{ __('lookup_section_offer') }}</h3>
            <dl class="mt-3 grid gap-3 sm:grid-cols-2">
                <div><dt class="text-slate-500">{{ __('Approved amount') }}</dt><dd class="font-semibold">{{ number_format((int) $application->approved_amount) }} ₫</dd></div>
                <div><dt class="text-slate-500">{{ __('Tenure') }}</dt><dd>{{ $application->tenure_months }} {{ __('months') }}</dd></div>
                <div><dt class="text-slate-500">{{ __('Est. monthly payment') }}</dt><dd>{{ $application->monthly_payment ? number_format((int) $application->monthly_payment).' ₫' : '—' }}</dd></div>
                <div><dt class="text-slate-500">{{ __('AI score') }}</dt><dd>{{ $application->score ?? '—' }}</dd></div>
                <div><dt class="text-slate-500">{{ __('Risk band') }}</dt><dd>{{ $application->risk_level ? match ((string) $application->risk_level) {
                    'low' => __('risk_low'),
                    'medium' => __('risk_medium'),
                    'elevated' => __('risk_elevated'),
                    default => (string) $application->risk_level,
                } : '—' }}</dd></div>
            </dl>
        </section>
    @endif

    @if($e && ($e->front_path || $e->back_path || $e->holding_front_path || $e->holding_back_path || $e->selfie_path))
        <section>
            <h3 class="text-xs font-bold uppercase tracking-wide text-slate-500">{{ __('ekyc_upload_title') }}</h3>
            <div class="mt-3 grid gap-4 sm:grid-cols-2">
                @php
                    $imgFields = [
                        'front' => ['path' => 'front_path', 'label' => __('CCCD front')],
                        'back' => ['path' => 'back_path', 'label' => __('CCCD back')],
                        'holding_front' => ['path' => 'holding_front_path', 'label' => __('CCCD holding front')],
                        'holding_back' => ['path' => 'holding_back_path', 'label' => __('CCCD holding back')],
                        'selfie' => ['path' => 'selfie_path', 'label' => __('Selfie (legacy)')],
                    ];
                @endphp
                @foreach ($imgFields as $fieldKey => $meta)
                    @php
                        $path = $e->{$meta['path']};
                        $url = $lookup
                            ? ($path ? route('application.lookup.image', ['loanApplication' => $application, 'field' => $fieldKey]) : null)
                            : ($path ? ($publicImageUrls[$fieldKey] ?? null) : null);
                    @endphp
                    @if($path && $url)
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                            <p class="text-xs font-semibold text-slate-600">{{ $meta['label'] }}</p>
                            <a href="{{ $url }}" target="_blank" rel="noopener" class="mt-2 inline-block text-sm font-medium text-blue-600 hover:underline">{{ __('View image') }}</a>
                        </div>
                    @endif
                @endforeach
            </div>
        </section>
    @endif

    @if($application->contract)
        <section>
            <h3 class="text-xs font-bold uppercase tracking-wide text-slate-500">{{ __('Contract') }}</h3>
            <dl class="mt-3 grid gap-3 sm:grid-cols-2">
                <div><dt class="text-slate-500">{{ __('field_code') }}</dt><dd class="font-mono">{{ $application->contract->code }}</dd></div>
                <div><dt class="text-slate-500">{{ __('field_signed_at') }}</dt><dd>{{ $application->contract->signed_at?->timezone(config('app.timezone'))->format('d/m/Y H:i') ?? '—' }}</dd></div>
            </dl>
        </section>
    @endif
</div>
