@php
    $slots = [
        'front' => ['label' => __('CCCD front'), 'hint' => __('ekyc_hint_scan_cccd')],
        'back' => ['label' => __('CCCD back'), 'hint' => __('ekyc_hint_scan_cccd')],
        'holding_front' => ['label' => __('CCCD holding front'), 'hint' => __('ekyc_hint_holding')],
        'holding_back' => ['label' => __('CCCD holding back'), 'hint' => __('ekyc_hint_holding')],
    ];
@endphp
<x-layouts.app title="{{ __('Documents') }}">
    <div class="mx-auto max-w-5xl px-4 py-10 sm:px-6">
        @include('apply.partials.stepper', ['step' => 3])
        <div class="apply-card"
            x-data="{
                previews: { front: null, back: null, holding_front: null, holding_back: null },
                handleFile(name, file) {
                    if (!file) return;
                    const reader = new FileReader();
                    reader.onload = (e) => { this.previews[name] = e.target.result; };
                    reader.readAsDataURL(file);
                }
            }">
            <h1 class="text-xl font-semibold tracking-tight text-slate-900 sm:text-2xl">{{ __('ekyc_upload_title') }}</h1>
            <p class="mt-1 text-sm text-slate-600">{{ __('ekyc_upload_subtitle') }}</p>

            <form method="post" action="{{ route('apply.ref.documents.save', ['reference' => $application->reference]) }}" enctype="multipart/form-data" class="mt-8">
                @csrf
                <div class="grid gap-6 sm:grid-cols-2">
                    @foreach ($slots as $field => $meta)
                        <div class="apply-upload-card">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">{{ $meta['label'] }}</p>
                                    <p class="mt-0.5 text-xs text-slate-500">{{ $meta['hint'] }}</p>
                                </div>
                                <span class="rounded bg-slate-100 px-1.5 py-0.5 text-[10px] font-medium uppercase tracking-wide text-slate-500">JPG / PNG / WebP</span>
                            </div>
                            <label class="apply-upload-zone mt-3">
                                <input type="file" name="{{ $field }}" accept="image/jpeg,image/png,image/webp,image/jpg" capture="environment" class="sr-only" required
                                    @change="handleFile('{{ $field }}', $event.target.files[0])">
                                <span class="text-sm text-slate-600">{{ __('Tap to choose or use camera') }}</span>
                                <span class="mt-1 text-xs text-slate-400">{{ __('ekyc_max_size', ['mb' => 5]) }}</span>
                                <div class="apply-preview" x-show="previews['{{ $field }}']" x-transition>
                                    <img :src="previews['{{ $field }}']" alt="" class="h-full w-full object-contain">
                                </div>
                            </label>
                        </div>
                    @endforeach
                </div>
                <div class="mt-8 flex justify-end border-t border-slate-100 pt-6">
                    <button type="submit" class="apply-btn-primary">{{ __('Submit dossier for validity check') }}</button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
