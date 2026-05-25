<x-layouts.app title="{{ __('Employment') }}">
    <div class="mx-auto max-w-3xl px-4 py-10 sm:px-6">
        @include('apply.partials.stepper', ['step' => 2])
        <div class="apply-card">
            <h1 class="text-xl font-semibold tracking-tight text-slate-900 sm:text-2xl">{{ __('Employment & banking') }}</h1>
            <p class="mt-1 text-sm text-slate-600">{{ __('Income and payout details help us size your offer.') }}</p>

            <form method="post" action="{{ route('apply.ref.employment.save', ['reference' => $application->reference]) }}" class="mt-8 grid gap-5 sm:grid-cols-2">
                @csrf
                <div class="sm:col-span-2">
                    <label class="apply-label" for="company">{{ __('Company') }}</label>
                    <input id="company" name="company" value="{{ old('company', $profile->company) }}" required class="apply-input">
                </div>
                <div class="sm:col-span-2">
                    <label class="apply-label" for="job_title">{{ __('Job title') }}</label>
                    <input id="job_title" name="job_title" value="{{ old('job_title', $profile->job_title) }}" required class="apply-input">
                </div>
                <div class="sm:col-span-2">
                    <label class="apply-label" for="monthly_income">{{ __('Monthly income (VND)') }}</label>
                    <input id="monthly_income" type="number" name="monthly_income" min="1" value="{{ old('monthly_income', $profile->monthly_income) }}" required class="apply-input">
                </div>
                <div>
                    <label class="apply-label" for="bank_name">{{ __('Bank name') }}</label>
                    <input id="bank_name" name="bank_name" value="{{ old('bank_name', $profile->bank_name) }}" required class="apply-input">
                </div>
                <div>
                    <label class="apply-label" for="bank_account">{{ __('Bank account') }}</label>
                    <input id="bank_account" name="bank_account" value="{{ old('bank_account', $profile->bank_account) }}" required class="apply-input">
                </div>
                <div class="sm:col-span-2 flex justify-end border-t border-slate-100 pt-6">
                    <button type="submit" class="apply-btn-primary">{{ __('Continue') }}</button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
