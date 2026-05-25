<x-layouts.app title="{{ __('Contract') }}">
    <div class="mx-auto max-w-3xl px-4 py-10 sm:px-6"
        x-data="{
            ready: false,
            async checkPdf() {
                try {
                    const res = await fetch(@js($contractPdfStatusUrl), { headers: { 'Accept': 'application/json' } });
                    const data = await res.json();
                    this.ready = data.ready;
                } catch (e) {}
            },
            init() {
                this.checkPdf();
                setInterval(() => this.checkPdf(), 2000);
            }
        }">
        @include('apply.partials.stepper', ['step' => 6])
        <div class="space-y-6">
            <div class="rounded-3xl border border-white/70 bg-white/95 p-6 shadow-xl sm:p-8">
                <h1 class="text-2xl font-bold text-slate-900">{{ __('Electronic contract') }}</h1>
                <p class="mt-2 text-sm text-slate-600">{{ __('Review your agreement, download the PDF, then sign with a one-time code sent to your email.') }}</p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a x-show="ready" x-transition href="{{ $contractDownloadUrl }}"
                        class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-900 shadow-sm hover:border-blue-300">
                        {{ __('Download PDF') }}
                    </a>
                    <div x-show="!ready" class="flex flex-1 items-center gap-3 rounded-2xl border border-dashed border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">
                        <span class="h-4 w-4 animate-spin rounded-full border-2 border-blue-500 border-t-transparent"></span>
                        {{ __('Preparing your contract…') }}
                    </div>
                </div>
            </div>
            <div class="rounded-3xl border border-white/70 bg-white/95 p-6 shadow-xl sm:p-8">
                <h2 class="text-lg font-semibold text-slate-900">{{ __('Sign with OTP') }}</h2>
                <form method="post" action="{{ $contractOtpUrl }}" class="mt-4">
                    @csrf
                    <button type="submit" class="rounded-2xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-blue-700">
                        {{ __('Email me a signing code') }}
                    </button>
                </form>
                <form method="post" action="{{ $contractConfirmUrl }}" class="mt-6 space-y-4">
                    @csrf
                    <div>
                        <label class="text-sm font-medium text-slate-700">{{ __('Signing code') }}</label>
                        <input name="code" type="text" maxlength="6" inputmode="numeric" required
                            class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-center font-mono text-2xl tracking-[0.3em] focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20">
                    </div>
                    <button type="submit" class="w-full rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-600/30 hover:brightness-110">
                        {{ __('Confirm signature') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
