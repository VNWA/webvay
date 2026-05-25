<x-layouts.app title="{{ __('Verify email') }}">
    <div class="mx-auto max-w-lg px-4 py-16 sm:px-6">
        <div class="apply-card" x-data="{ sent: true }">
            <h1 class="text-xl font-semibold text-slate-900">{{ __('Enter your code') }}</h1>
            <p class="mt-2 text-sm text-slate-600">{{ __('We sent a 6-digit code to') }} <span class="font-semibold text-slate-900">{{ $email }}</span></p>
            <form method="post" action="{{ route('apply.verify.submit') }}" class="mt-8 space-y-5">
                @csrf
                <div>
                    <label class="apply-label" for="otp-code">{{ __('Verification code') }}</label>
                    <input id="otp-code" name="code" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="6" autocomplete="one-time-code" required
                        class="apply-input text-center text-2xl font-mono tracking-[0.35em]">
                </div>
                <button type="submit" class="apply-btn-primary w-full">{{ __('Verify & continue') }}</button>
            </form>
            <form method="post" action="{{ route('apply.otp.store') }}" class="mt-4">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">
                <button type="submit" class="text-sm font-semibold text-blue-700 hover:underline">{{ __('Resend code') }}</button>
            </form>
        </div>
    </div>
</x-layouts.app>
