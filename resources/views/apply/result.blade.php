<x-layouts.app title="{{ __('apply_result_page_title') }}">
    <div class="mx-auto max-w-3xl px-4 py-12 sm:px-6"
        x-data="{
            shown: {{ (int) $application->approved_amount }},
            display: 0,
            init() {
                const target = this.shown;
                const duration = 1600;
                const start = performance.now();
                const tick = (now) => {
                    const p = Math.min(1, (now - start) / duration);
                    this.display = Math.floor(target * p);
                    if (p < 1) requestAnimationFrame(tick);
                    else {
                        this.display = target;
                        if (window.confetti) {
                            window.confetti({ particleCount: 120, spread: 70, origin: { y: 0.6 } });
                        }
                    }
                };
                requestAnimationFrame(tick);
            }
        }">
        @include('apply.partials.stepper', ['step' => 5])
        <div class="rounded-3xl border border-emerald-100 bg-gradient-to-br from-white to-emerald-50/60 p-8 text-center shadow-2xl shadow-emerald-500/10 sm:p-12">
            <p class="text-sm font-semibold uppercase tracking-wide text-emerald-700">{{ __('apply_result_valid_badge') }}</p>
            <h1 class="mt-3 text-3xl font-black text-slate-900 sm:text-4xl">{{ __('apply_result_valid_heading') }}</h1>
            <p class="mt-4 text-slate-600">{{ __('apply_result_offer_intro') }}</p>
            <p class="mt-6 text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('apply_result_proposed_amount') }}</p>
            <p class="mt-2 text-4xl font-black text-blue-700 sm:text-5xl">
                <span x-text="new Intl.NumberFormat('vi-VN').format(display)"></span> ₫
            </p>
            <div class="mt-8 grid gap-4 rounded-2xl bg-white/80 p-6 text-left shadow-inner sm:grid-cols-2">
                <div>
                    <p class="text-xs font-semibold uppercase text-slate-500">{{ __('Tenure') }}</p>
                    <p class="text-lg font-bold text-slate-900">{{ $application->tenure_months }} {{ __('months') }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-slate-500">{{ __('Est. monthly payment') }}</p>
                    <p class="text-lg font-bold text-slate-900">{{ number_format((int) $application->monthly_payment) }} ₫</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-slate-500">{{ __('AI score') }}</p>
                    <p class="text-lg font-bold text-slate-900">{{ $application->score }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-slate-500">{{ __('Risk band') }}</p>
                    <p class="text-lg font-bold text-slate-900">{{ match ((string) $application->risk_level) {
                        'low' => __('risk_low'),
                        'medium' => __('risk_medium'),
                        'elevated' => __('risk_elevated'),
                        default => (string) $application->risk_level,
                    } }}</p>
                </div>
            </div>
            <form method="post" action="{{ route('apply.ref.result.continue', ['reference' => $application->reference]) }}" class="mt-10">
                @csrf
                <button type="submit" class="w-full rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 py-4 text-sm font-semibold text-white shadow-lg shadow-blue-600/30 hover:brightness-110 sm:w-auto sm:px-12">
                    {{ __('Continue to contract') }}
                </button>
            </form>
        </div>
    </div>
</x-layouts.app>
