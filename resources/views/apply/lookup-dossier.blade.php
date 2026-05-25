<x-layouts.app title="{{ __('lookup_dossier_title') }}">
    <div class="mx-auto max-w-3xl px-4 py-16 sm:px-6">
        <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-xl">
            <h1 class="text-2xl font-bold text-slate-900">{{ __('lookup_dossier_title') }}</h1>
            <p class="mt-2 text-sm text-slate-600">{{ __('lookup_dossier_intro') }}</p>
            <div class="mt-8">
                @include('apply.partials.dossier-detail', ['application' => $application, 'publicImageUrls' => null])
            </div>
            <a href="{{ route('application.lookup.create') }}" class="mt-8 inline-flex text-sm font-semibold text-blue-600 hover:underline">{{ __('lookup_back_form') }}</a>
        </div>
    </div>
</x-layouts.app>
