<x-layouts.app title="{{ __('Sign in') }}">
    <div class="mx-auto max-w-lg px-4 py-16 sm:px-6">
        <div class="apply-card max-w-lg mx-auto">
            <h1 class="text-xl font-semibold text-slate-900">{{ __('apply_start_heading') }}</h1>
            <p class="mt-2 text-sm text-slate-600">{{ __('apply_start_intro', ['m' => 5]) }}</p>
            <p class="mt-1 text-xs text-slate-500">{{ __('otp_gmail_only_hint') }}</p>
            <form method="post" action="{{ route('apply.otp.store') }}" class="mt-8 space-y-5">
                @csrf
                <div>
                    <label class="apply-label" for="otp-email">{{ __('Email') }}</label>
                    <input id="otp-email" name="email" type="email" required value="{{ old('email') }}" class="apply-input">
                </div>
                <div>
                    <label class="apply-label" for="otp-phone">{{ __('Phone number') }}</label>
                    <input id="otp-phone" name="phone" type="tel" inputmode="tel" required value="{{ old('phone') }}" class="apply-input" placeholder="0912345678">
                    <p class="mt-1 text-xs text-slate-500">{{ __('apply_phone_hint') }}</p>
                </div>
                <div>
                    <label class="apply-label" for="desired_amount">{{ __('Desired loan (VND)') }}</label>
                    <input id="desired_amount" name="desired_amount" type="number" step="500000" min="3000000" max="50000000"
                        value="{{ old('desired_amount', $desiredAmount) }}" class="apply-input">
                </div>
                <button type="submit" class="apply-btn-primary w-full">
                    {{ __('Send verification code') }}
                </button>
            </form>
        </div>
    </div>
</x-layouts.app>
