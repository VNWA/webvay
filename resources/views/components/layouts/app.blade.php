@props([
    'title' => null,
])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ? $title.' — ' : '' }}{{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full bg-slate-50 text-slate-900 antialiased">
    <div class="flex min-h-full flex-col">
        <header class="sticky top-0 z-40 border-b border-white/40 bg-white/80 backdrop-blur-md" x-data="{ mobileOpen: false }">
            <div class="mx-auto flex max-w-6xl items-center justify-between gap-3 px-4 py-4 sm:px-6">
                <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-2 font-semibold tracking-tight text-slate-900">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 text-sm font-bold text-white shadow-lg shadow-blue-500/30">FV</span>
                    <span class="text-lg">{{ config('app.name', 'FinVay') }}</span>
                </a>
                <nav class="hidden flex-wrap items-center justify-center gap-x-4 gap-y-2 text-sm font-medium text-slate-600 lg:flex xl:gap-x-6">
                    <a href="{{ route('pages.about') }}" class="whitespace-nowrap hover:text-blue-700">{{ __('nav_about') }}</a>
                    <a href="{{ route('pages.services') }}" class="whitespace-nowrap hover:text-blue-700">{{ __('nav_services') }}</a>
                    <a href="{{ route('blog.index') }}" class="whitespace-nowrap hover:text-blue-700">{{ __('nav_news') }}</a>
                    <a href="{{ route('contact.create') }}" class="whitespace-nowrap hover:text-blue-700">{{ __('nav_contact') }}</a>
                    <a href="{{ route('application.lookup.create') }}" class="whitespace-nowrap hover:text-blue-700">{{ __('nav_lookup') }}</a>
                    <span class="hidden text-slate-300 xl:inline" aria-hidden="true">|</span>
                    <a href="{{ route('home') }}#features" class="whitespace-nowrap hover:text-blue-700">{{ __('Features') }}</a>
                    <a href="{{ route('home') }}#faq" class="whitespace-nowrap hover:text-blue-700">{{ __('FAQ') }}</a>
                    <a href="{{ route('apply.start') }}" class="whitespace-nowrap rounded-full bg-blue-600 px-4 py-2 text-white shadow-md shadow-blue-600/25 hover:bg-blue-700">{{ __('Apply now') }}</a>
                </nav>
                <div class="flex shrink-0 items-center gap-2 lg:hidden">
                    <a href="{{ route('apply.start') }}" class="text-sm font-semibold text-blue-700">{{ __('Start') }}</a>
                    <button type="button" class="rounded-lg border border-slate-200 p-2 text-slate-700 hover:bg-slate-50" @click="mobileOpen = !mobileOpen" :aria-expanded="mobileOpen" aria-controls="mobile-nav">
                        <span class="sr-only">{{ __('nav_menu') }}</span>
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                </div>
            </div>
            <div id="mobile-nav" x-show="mobileOpen" x-cloak x-transition class="border-t border-slate-100 bg-white/95 lg:hidden">
                <nav class="mx-auto flex max-w-6xl flex-col gap-1 px-4 py-3 text-sm font-medium text-slate-700">
                    <a href="{{ route('pages.about') }}" class="rounded-lg px-3 py-2 hover:bg-slate-50">{{ __('nav_about') }}</a>
                    <a href="{{ route('pages.services') }}" class="rounded-lg px-3 py-2 hover:bg-slate-50">{{ __('nav_services') }}</a>
                    <a href="{{ route('blog.index') }}" class="rounded-lg px-3 py-2 hover:bg-slate-50">{{ __('nav_news') }}</a>
                    <a href="{{ route('contact.create') }}" class="rounded-lg px-3 py-2 hover:bg-slate-50">{{ __('nav_contact') }}</a>
                    <a href="{{ route('application.lookup.create') }}" class="rounded-lg px-3 py-2 hover:bg-slate-50">{{ __('nav_lookup') }}</a>
                    <a href="{{ route('home') }}#features" class="rounded-lg px-3 py-2 hover:bg-slate-50">{{ __('Features') }}</a>
                    <a href="{{ route('home') }}#faq" class="rounded-lg px-3 py-2 hover:bg-slate-50">{{ __('FAQ') }}</a>
                </nav>
            </div>
        </header>

        <main class="flex-1">
            @if (session('status'))
                <div class="mx-auto max-w-3xl px-4 pt-6">
                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900 shadow-sm">
                        {{ session('status') }}
                    </div>
                </div>
            @endif
            @if ($errors->any())
                <div class="mx-auto max-w-3xl px-4 pt-6">
                    <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-900 shadow-sm">
                        <ul class="list-inside list-disc space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
            {{ $slot }}
        </main>

        <footer class="mt-auto border-t border-slate-200 bg-white">
            <div class="mx-auto grid max-w-6xl gap-8 px-4 py-12 sm:grid-cols-2 sm:px-6 lg:grid-cols-4">
                <div>
                    <div class="flex items-center gap-2 font-semibold text-slate-900">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-600 text-xs font-bold text-white">FV</span>
                        {{ config('app.name', 'FinVay') }}
                    </div>
                    <p class="mt-3 text-sm text-slate-600">{{ __('footer_company_blurb') }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-slate-900">{{ __('footer_explore') }}</h3>
                    <ul class="mt-3 space-y-2 text-sm text-slate-600">
                        <li><a href="{{ route('pages.about') }}" class="hover:text-blue-700">{{ __('nav_about') }}</a></li>
                        <li><a href="{{ route('pages.services') }}" class="hover:text-blue-700">{{ __('nav_services') }}</a></li>
                        <li><a href="{{ route('blog.index') }}" class="hover:text-blue-700">{{ __('nav_news') }}</a></li>
                        <li><a href="{{ route('contact.create') }}" class="hover:text-blue-700">{{ __('nav_contact') }}</a></li>
                        <li><a href="{{ route('application.lookup.create') }}" class="hover:text-blue-700">{{ __('nav_lookup') }}</a></li>
                    </ul>
                    <h3 class="mt-6 text-sm font-semibold text-slate-900">{{ __('Legal') }}</h3>
                    <ul class="mt-3 space-y-2 text-sm text-slate-600">
                        <li><a href="#" class="hover:text-blue-700">{{ __('Terms of service') }}</a></li>
                        <li><a href="#" class="hover:text-blue-700">{{ __('Privacy policy') }}</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-slate-900">{{ __('Contact') }}</h3>
                    <p class="mt-3 text-sm text-slate-600">
                        <a href="mailto:{{ config('finvay.support_email') }}"
                            class="text-blue-600 hover:underline">{{ config('finvay.support_email') }}</a>
                    </p>
                    <p class="mt-2 text-sm"><a href="{{ route('contact.create') }}" class="font-medium text-blue-600 hover:underline">{{ __('nav_contact') }}</a></p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-slate-900">{{ __('Social') }}</h3>
                    <div class="mt-3 flex flex-wrap gap-3 text-sm text-slate-600">
                        <a href="#" class="rounded-xl border border-slate-200 px-3 py-1.5 hover:border-blue-300 hover:text-blue-700" aria-label="{{ __('social_linkedin') }}">{{ __('social_linkedin') }}</a>
                        <a href="#" class="rounded-xl border border-slate-200 px-3 py-1.5 hover:border-blue-300 hover:text-blue-700" aria-label="{{ __('social_x') }}">{{ __('social_x') }}</a>
                    </div>
                </div>
            </div>
            <div class="border-t border-slate-100 py-4 text-center text-xs text-slate-500">
                © {{ date('Y') }} {{ config('app.name') }}. {{ __('All rights reserved.') }}
            </div>
        </footer>

        @isset($bottomBar)
            <div class="pb-20 sm:pb-0">
                {{ $bottomBar }}
            </div>
        @endisset
    </div>
</body>
</html>
