<x-layouts.app title="{{ __('lookup_page_title') }}">
    <div class="mx-auto max-w-lg px-4 py-16 sm:px-6">
        <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-xl shadow-slate-500/10">
            <h1 class="text-2xl font-bold text-slate-900">{{ __('lookup_page_heading') }}</h1>
            <p class="mt-2 text-sm text-slate-600">{{ __('lookup_page_intro') }}</p>
            <form method="post" action="{{ route('application.lookup.store') }}" class="mt-8 space-y-5">
                @csrf
                <div>
                    <label for="reference" class="block text-sm font-medium text-slate-700">{{ __('Application reference') }}</label>
                    <input id="reference" name="reference" type="text" value="{{ old('reference') }}" required autocomplete="off"
                        class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-3 text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20" />
                    @error('reference')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700">{{ __('Email') }}</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email"
                        class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-3 text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20" />
                    @error('email')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="w-full rounded-2xl bg-blue-600 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-600/25 hover:bg-blue-700">
                    {{ __('lookup_submit') }}
                </button>
            </form>
        </div>
    </div>
</x-layouts.app>
