<x-layouts.app title="{{ __('apply_pending_admin_title') }}">
    <div class="mx-auto max-w-2xl px-4 py-16 text-center sm:px-6">
        @include('apply.partials.stepper', ['step' => 5])
        <div class="rounded-3xl border border-amber-100 bg-white p-10 shadow-xl shadow-amber-500/10">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-amber-400 text-2xl text-white">…</div>
            <h1 class="mt-6 text-2xl font-bold text-slate-900">{{ __('apply_pending_admin_title') }}</h1>
            <p class="mt-3 text-slate-600">{{ __('apply_pending_admin_body', ['ref' => $application->reference]) }}</p>
            <p class="mt-4 text-sm text-slate-500">{{ __('apply_pending_admin_email_hint') }}</p>
            <p class="mt-2 text-sm text-slate-500">{{ __('apply_pending_admin_hint') }}</p>
            <a href="{{ route('application.lookup.create') }}" class="mt-8 inline-flex rounded-2xl border border-slate-200 px-6 py-3 text-sm font-semibold text-slate-800 hover:bg-slate-50">
                {{ __('nav_lookup') }}
            </a>
        </div>
    </div>
</x-layouts.app>
