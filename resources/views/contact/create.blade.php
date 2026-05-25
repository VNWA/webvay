<x-layouts.app :title="__('nav_contact')">
    <div class="mx-auto max-w-xl px-4 py-12 sm:px-6">
        <h1 class="text-3xl font-bold tracking-tight text-slate-900">{{ __('nav_contact') }}</h1>
        <p class="mt-2 text-slate-600">{{ __('contact_intro') }}</p>

        <form action="{{ route('contact.store') }}" method="post" class="mt-8 space-y-5">
            @csrf
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700">{{ __('Full name') }}</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required maxlength="120"
                    class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700">{{ __('Email') }}</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required maxlength="255"
                    class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
            </div>
            <div>
                <label for="phone" class="block text-sm font-medium text-slate-700">{{ __('contact_phone_optional') }}</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" maxlength="32"
                    class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
            </div>
            <div>
                <label for="subject" class="block text-sm font-medium text-slate-700">{{ __('contact_subject_optional') }}</label>
                <input type="text" name="subject" id="subject" value="{{ old('subject') }}" maxlength="200"
                    class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
            </div>
            <div>
                <label for="message" class="block text-sm font-medium text-slate-700">{{ __('contact_message') }}</label>
                <textarea name="message" id="message" rows="5" required maxlength="5000"
                    class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">{{ old('message') }}</textarea>
            </div>
            <button type="submit"
                class="w-full rounded-2xl bg-blue-600 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-600/25 transition hover:bg-blue-700">
                {{ __('contact_send') }}
            </button>
        </form>
    </div>
</x-layouts.app>
