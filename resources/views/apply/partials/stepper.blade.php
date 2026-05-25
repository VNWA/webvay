@props(['step' => 1])
@php
    $steps = [
        1 => __('Personal'),
        2 => __('Employment'),
        3 => __('Documents'),
        4 => __('apply_step_validity'),
        5 => __('Offer'),
        6 => __('Contract'),
        7 => __('Done'),
    ];
@endphp
<div class="mx-auto mb-8 max-w-5xl overflow-x-auto px-1">
    <div class="flex min-w-max items-start justify-center gap-2 sm:gap-4">
        @foreach ($steps as $i => $label)
            <div class="flex w-[5.25rem] flex-col items-center gap-1.5 text-center sm:w-24">
                <span @class([
                    'flex h-8 w-8 shrink-0 items-center justify-center rounded-md border text-xs font-semibold tabular-nums',
                    'border-blue-600 bg-blue-600 text-white' => $step >= $i,
                    'border-slate-200 bg-white text-slate-400' => $step < $i,
                ])>{{ $i }}</span>
                <span @class([
                    'text-[10px] font-medium leading-snug sm:text-[11px]',
                    $step === $i ? 'text-blue-700' : 'text-slate-500',
                ])>{{ $label }}</span>
            </div>
        @endforeach
    </div>
</div>
