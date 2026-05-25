<x-layouts.app title="{{ __('apply_rejected_title') }}">
    <div class="mx-auto max-w-2xl px-4 py-16 text-center sm:px-6">
        <div class="rounded-3xl border border-rose-100 bg-white p-10 shadow-xl">
            <h1 class="text-2xl font-bold text-rose-900">{{ __('apply_rejected_title') }}</h1>
            <p class="mt-3 text-slate-600">{{ __('apply_rejected_intro', ['ref' => $application->reference]) }}</p>
            @if($application->admin_notes)
                <div class="mt-6 rounded-2xl border border-rose-100 bg-rose-50 p-4 text-left text-sm text-rose-950">
                    {{ $application->admin_notes }}
                </div>
            @endif
            <a href="{{ route('apply.start') }}" class="mt-8 inline-flex rounded-2xl bg-slate-900 px-8 py-3 text-sm font-semibold text-white hover:bg-slate-800">
                {{ __('apply_rejected_new_cta') }}
            </a>
        </div>
    </div>
</x-layouts.app>
