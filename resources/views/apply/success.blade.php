<x-layouts.app title="{{ __('apply_success_page_title') }}">
    <div class="mx-auto max-w-2xl px-4 py-16 text-center sm:px-6"
        x-init="if (window.confetti) { window.confetti({ particleCount: 80, spread: 60, origin: { y: 0.4 } }); }">
        @include('apply.partials.stepper', ['step' => 7])
        <div class="rounded-3xl border border-emerald-100 bg-white p-10 shadow-2xl shadow-emerald-500/10">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-emerald-500 text-3xl text-white shadow-lg shadow-emerald-500/40">✓</div>
            <h1 class="mt-6 text-3xl font-bold text-slate-900">{{ __('apply_success_heading') }}</h1>
            <p class="mt-3 text-slate-600">{{ __('apply_success_signed', ['ref' => $application->reference]) }}</p>
            <p class="mt-4 text-sm text-slate-600">{{ __('apply_success_email_lookup') }}</p>
            <div class="mt-6 flex flex-col items-center gap-3 sm:flex-row sm:justify-center">
                <a href="{{ route('application.lookup.create') }}" class="inline-flex rounded-2xl border border-blue-200 bg-blue-50 px-6 py-3 text-sm font-semibold text-blue-800 hover:bg-blue-100">
                    {{ __('apply_success_lookup_cta') }}
                </a>
                <a href="{{ route('home') }}" class="inline-flex rounded-2xl bg-blue-600 px-8 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-600/30 hover:bg-blue-700">
                    {{ __('Back to home') }}
                </a>
            </div>
        </div>
    </div>
</x-layouts.app>
