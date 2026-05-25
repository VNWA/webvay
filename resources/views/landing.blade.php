<x-layouts.app title="{{ __('Home') }}">
    <section class="relative overflow-hidden bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100">
        <div class="pointer-events-none absolute inset-0">
            <div class="absolute -left-20 top-20 h-72 w-72 rounded-full bg-blue-400/20 blur-3xl"></div>
            <div class="absolute -right-10 bottom-10 h-80 w-80 rounded-full bg-indigo-400/20 blur-3xl"></div>
        </div>
        <div class="relative mx-auto grid max-w-6xl items-center gap-12 px-4 py-16 sm:px-6 lg:grid-cols-2 lg:py-24">
            <div>
                <div
                    class="inline-flex items-center gap-2 rounded-full border border-white/60 bg-white/70 px-3 py-1 text-xs font-medium text-blue-700 shadow-sm backdrop-blur">
                    <span class="h-2 w-2 animate-pulse rounded-full bg-emerald-500"></span>
                    {{ __('Instant decisions • Bank-grade security') }}
                </div>
                <h1 class="mt-6 text-4xl font-extrabold tracking-tight text-slate-900 sm:text-5xl lg:text-6xl">
                    {{ __('Borrow smarter.') }}
                    <span
                        class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">{{ __('Live in minutes.') }}</span>
                </h1>
                <p class="mt-5 max-w-xl text-lg text-slate-600">
                    {{ __('Premium digital lending with guided onboarding, AI-assisted verification, and transparent offers tailored to your profile.') }}
                </p>
                <div class="mt-10 flex flex-wrap items-center gap-4" x-data="{
                    amount: {{ $desiredAmount }},
                    format(n) { return new Intl.NumberFormat('vi-VN').format(n) + ' ₫' }
                }">
                    <div
                        class="w-full max-w-md rounded-3xl border border-white/60 bg-white/80 p-6 shadow-xl shadow-blue-900/5 backdrop-blur">
                        <label class="text-sm font-medium text-slate-700">{{ __('How much do you need?') }}</label>
                        <input type="range" min="3000000" max="50000000" step="500000" x-model.number="amount"
                            class="mt-4 w-full accent-blue-600">
                        <div class="mt-4 flex items-baseline justify-between">
                            <span class="text-sm text-slate-500">{{ __('Selected') }}</span>
                            <span class="text-2xl font-bold text-slate-900" x-text="format(amount)"></span>
                        </div>
                        <a :href="`{{ route('apply.start') }}?amount=${amount}`"
                            class="mt-6 flex w-full items-center justify-center rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 px-4 py-3 text-center text-sm font-semibold text-white shadow-lg shadow-blue-600/30 transition hover:brightness-110">
                            {{ __('Start application') }}
                        </a>
                    </div>
                </div>
            </div>
            <div class="relative">
                <div
                    class="absolute -inset-6 -z-10 rounded-[2.5rem] bg-gradient-to-tr from-blue-500/10 to-indigo-500/10 blur-2xl">
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div
                        class="rounded-3xl border border-white/70 bg-white/90 p-5 shadow-lg shadow-slate-900/5 backdrop-blur transition duration-500 hover:-translate-y-1">
                        <p class="text-xs font-medium text-blue-600">{{ __('Approval') }}</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">{{ __('hero_stat_approval_rate') }}</p>
                        <p class="text-sm text-slate-600">{{ __('Instant pre-screen pass rate ') }}</p>
                    </div>
                    <div
                        class="rounded-3xl border border-white/70 bg-white/90 p-5 shadow-lg shadow-slate-900/5 backdrop-blur transition duration-500 hover:-translate-y-1 sm:translate-y-8">
                        <p class="text-xs font-medium text-indigo-600">{{ __('Average time') }}</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">{{ __('hero_stat_avg_time') }}</p>
                        <p class="text-sm text-slate-600">{{ __('From start to offer ') }}</p>
                    </div>
                    <div
                        class="rounded-3xl border border-white/70 bg-white/90 p-5 shadow-lg shadow-slate-900/5 backdrop-blur transition duration-500 hover:-translate-y-1 sm:col-span-2">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-medium text-emerald-600">{{ __('Live queue') }}</p>
                                <p class="mt-1 text-lg font-semibold text-slate-900">{{ __('128 applicants') }}</p>
                            </div>
                            <span
                                class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">{{ __('Stable') }}</span>
                        </div>
                        <div class="mt-4 h-2 overflow-hidden rounded-full bg-slate-100">
                            <div
                                class="h-full w-2/3 animate-pulse rounded-full bg-gradient-to-r from-emerald-400 to-blue-500">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="features" class="mx-auto max-w-6xl px-4 py-20 sm:px-6">
        <h2 class="text-center text-3xl font-bold tracking-tight text-slate-900">{{ __('Built for speed and trust') }}
        </h2>
        <p class="mx-auto mt-3 max-w-2xl text-center text-slate-600">
            {{ __('Every interaction is crafted to feel like a top-tier digital bank—without the paperwork.') }}</p>
        <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ([['icon' => '⚡', 'title' => __('Fast approval'), 'body' => __('Adaptive flows and instant scoring keep momentum high.')], ['icon' => '🔐', 'title' => __('Secure verification'), 'body' => __('OTP login, private document vault, and audit trails.')], ['icon' => '📉', 'title' => __('Competitive rates'), 'body' => __('Transparent amortization preview on every offer.')], ['icon' => '💬', 'title' => __('24/7 support'), 'body' => __('Always-on concierge experience with human tone.')]] as $card)
                <div
                    class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                    <div class="text-3xl">{{ $card['icon'] }}</div>
                    <h3 class="mt-3 text-lg font-semibold text-slate-900">{{ $card['title'] }}</h3>
                    <p class="mt-2 text-sm text-slate-600">{{ $card['body'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <section class="border-y border-slate-200 bg-white py-16">
        <div class="mx-auto flex max-w-6xl flex-col gap-10 px-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">{{ __('Trusted by modern teams') }}</h2>
                <p class="mt-2 text-slate-600">
                    {{ __('Security badges, audited processes, and delightful micro-interactions.') }}</p>
            </div>
            <div class="flex flex-wrap gap-3 text-xs font-medium leading-snug text-slate-600 sm:text-sm">
                <span class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2">{{ __('badge_pci') }}</span>
                <span class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2">{{ __('badge_tls') }}</span>
                <span class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2">{{ __('badge_soc2') }}</span>
            </div>
        </div>
        <div class="mx-auto mt-10 grid max-w-6xl gap-6 px-4 sm:grid-cols-3 sm:px-6">
            @foreach ([['name' => 'Lan P.', 'quote' => __('testimonial_quote_flow')], ['name' => 'Minh T.', 'quote' => __('“Feels premium—animations and clarity are top notch.”')], ['name' => 'Hoa N.', 'quote' => __('“Loved the transparency on monthly payments.”')]] as $review)
                <figure
                    class="rounded-3xl border border-slate-100 bg-gradient-to-br from-white to-slate-50 p-6 shadow-sm">
                    <blockquote class="text-sm text-slate-700">{{ $review['quote'] }}</blockquote>
                    <figcaption class="mt-4 text-xs font-medium text-blue-700">{{ $review['name'] }}</figcaption>
                </figure>
            @endforeach
        </div>
    </section>

    <section id="faq" class="mx-auto max-w-3xl px-4 py-20 sm:px-6">
        <h2 class="text-center text-3xl font-bold text-slate-900">{{ __('FAQ') }}</h2>
        <div class="mt-8 space-y-4" x-data="{ open: 1 }">
            @foreach ([['q' => __('faq_what_is_finvay_q'), 'a' => __('faq_what_is_finvay_a')], ['q' => __('How do I sign in?'), 'a' => __('We use email OTP codes—no passwords for customers.')], ['q' => __('Where are my documents stored?'), 'a' => __('Uploads are stored privately on the server disk with randomized filenames.')]] as $i => $faq)
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <button type="button"
                        class="flex w-full items-center justify-between px-5 py-4 text-left text-sm font-semibold text-slate-900"
                        @click="open = open === {{ $i }} ? null : {{ $i }}}">
                        {{ $faq['q'] }}
                        <span class="text-blue-600" x-text="open === {{ $i }} ? '−' : '+'"></span>
                    </button>
                    <div x-show="open === {{ $i }}" x-transition
                        class="border-t border-slate-100 px-5 py-4 text-sm text-slate-600">
                        {{ $faq['a'] }}
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <x-slot name="bottomBar">
        <div
            class="fixed bottom-0 left-0 right-0 z-30 border-t border-white/60 bg-white/90 p-4 backdrop-blur-md sm:hidden">
            <a href="{{ route('apply.start') }}"
                class="flex w-full items-center justify-center rounded-2xl bg-blue-600 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-600/30">
                {{ __('Continue application') }}
            </a>
        </div>
    </x-slot>
</x-layouts.app>
