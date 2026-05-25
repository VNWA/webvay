<x-layouts.app title="{{ __('lookup_summary_title') }}">
    <div class="mx-auto max-w-3xl px-4 py-16 sm:px-6">
        <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-xl">
            <h1 class="text-2xl font-bold text-slate-900">{{ __('lookup_summary_title') }}</h1>
            <p class="mt-2 text-sm text-slate-600">{{ __('lookup_summary_intro') }}</p>
            <div class="mt-8">
                @include('apply.partials.dossier-detail', ['application' => $application, 'publicImageUrls' => $publicImageUrls])
            </div>
            <p class="mt-6 text-xs text-slate-500">{{ __('lookup_summary_hint') }}</p>
        </div>
    </div>
</x-layouts.app>
