@props([
    'type' => 'cover',
    'size' => 'md',
    'showLabel' => true,
])

@php
    $avatarSizes = [
        'xs' => 'h-7 w-7',
        'sm' => 'h-9 w-9',
        'md' => 'h-11 w-11',
        'lg' => 'h-14 w-14',
        'xl' => 'h-20 w-20',
    ];
    $iconSizes = [
        'xs' => 'h-3.5 w-3.5',
        'sm' => 'h-4 w-4',
        'md' => 'h-5 w-5',
        'lg' => 'h-6 w-6',
        'xl' => 'h-8 w-8',
    ];
    $labelSizes = [
        'xs' => 'text-[8px]',
        'sm' => 'text-[9px]',
        'md' => 'text-[10px]',
        'lg' => 'hidden',
        'xl' => 'hidden',
    ];
    $boxClass = $avatarSizes[$size] ?? $avatarSizes['md'];
    $iconClass = $iconSizes[$size] ?? $iconSizes['md'];
    $labelClass = $labelSizes[$size] ?? $labelSizes['md'];
    $isAvatar = $type === 'avatar';
    $label = $isAvatar ? __('blog_no_avatar_image') : __('blog_no_cover_image');
@endphp

<div
    {{ $attributes->class([
        'flex flex-col items-center justify-center gap-1 text-center',
        'rounded-full border-2 border-dashed border-slate-300 bg-slate-100 text-slate-400' => $isAvatar,
        'border border-dashed border-slate-200 bg-gradient-to-br from-slate-50 to-slate-100 text-slate-400' => ! $isAvatar,
        $boxClass => $isAvatar,
    ]) }}
    role="img"
    aria-label="{{ $label }}">
    @if ($isAvatar)
        <svg class="{{ $iconClass }} shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
            stroke-width="1.5" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
        </svg>
    @else
        <svg class="h-10 w-10 shrink-0 opacity-70 sm:h-12 sm:w-12" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 24 24" stroke-width="1.25" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" />
        </svg>
    @endif
    @if ($showLabel && ! $isAvatar)
        <span class="px-3 text-xs font-medium text-slate-500">{{ $label }}</span>
    @elseif ($showLabel && $isAvatar && in_array($size, ['md', 'lg', 'xl'], true))
        <span class="{{ $labelClass }} font-medium leading-none text-slate-500">{{ __('blog_no_image_short') }}</span>
    @endif
</div>
