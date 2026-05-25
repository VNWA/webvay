<x-layouts.app title="{{ __('apply_ai_page_title') }}">
    <div class="mx-auto max-w-xl px-4 py-16 sm:px-6"
        x-data="{
            label: @js(__('apply_ai_initial_label')),
            percent: 0,
            timer: null,
            async tick() {
                try {
                    const res = await fetch(@js($aiStatusUrl), { headers: { 'Accept': 'application/json' } });
                    const data = await res.json();
                    this.percent = data.ai_progress?.percent ?? this.percent;
                    this.label = data.ai_progress?.label ?? this.label;
                    if (data.redirect) {
                        clearInterval(this.timer);
                        window.location = data.redirect;
                    }
                } catch (e) {}
            },
            init() {
                this.timer = setInterval(() => this.tick(), 1200);
                this.tick();
            }
        }">
        @include('apply.partials.stepper', ['step' => 4])
        <div class="rounded-3xl border border-white/70 bg-white/95 p-8 text-center shadow-2xl shadow-blue-900/10 backdrop-blur">
            <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-gradient-to-br from-blue-600 to-indigo-600 shadow-lg shadow-blue-600/40">
                <svg class="h-10 w-10 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>
            </div>
            <p class="mt-6 text-lg font-semibold text-slate-900" x-text="label"></p>
            <p class="mt-2 text-sm text-slate-600">{{ __('apply_ai_keep_open_hint') }}</p>
            <div class="mt-8 h-3 overflow-hidden rounded-full bg-slate-100">
                <div class="h-full rounded-full bg-gradient-to-r from-blue-500 via-indigo-500 to-cyan-400 transition-all duration-700 ease-out"
                    :style="`width: ${percent}%`"></div>
            </div>
            <p class="mt-3 text-3xl font-black text-blue-700" x-text="`${percent}%`"></p>
        </div>
    </div>
</x-layouts.app>
