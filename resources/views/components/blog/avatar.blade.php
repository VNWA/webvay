@props([
    'name' => '',
    'url' => null,
    'initials' => null,
    'size' => 'md',
    'ring' => false,
    /** placeholder | initials | auto (image → placeholder, không dùng chữ cái) */
    'fallback' => 'placeholder',
])

@php
    $sizes = [
        'xs' => 'h-7 w-7 text-[10px]',
        'sm' => 'h-9 w-9 text-xs',
        'md' => 'h-11 w-11 text-sm',
        'lg' => 'h-14 w-14 text-base',
        'xl' => 'h-20 w-20 text-xl',
    ];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $label = $initials ?? \App\Models\BlogPost::initialsFromName($name);
    $ringClass = $ring ? 'ring-2 ring-white ring-offset-2 ring-offset-white' : '';
    $useInitials = $fallback === 'initials';
    $uid = 'av-' . substr(md5($name . $size . ($url ?? '')), 0, 8);
@endphp

@if ($url)
    <span class="relative inline-flex shrink-0 {{ $sizeClass }}">
        <img src="{{ $url }}" alt="{{ $name }}"
            id="{{ $uid }}-img"
            {{ $attributes->class(["rounded-full object-cover bg-slate-100 w-full h-full {$ringClass}"]) }}
            loading="lazy" decoding="async"
            onerror="this.classList.add('hidden'); document.getElementById('{{ $uid }}-ph')?.classList.remove('hidden');">
        <span id="{{ $uid }}-ph" class="absolute inset-0 hidden">
            <x-blog.media-placeholder type="avatar" :size="$size" :show-label="in_array($size, ['lg', 'xl'], true)"
                class="h-full w-full {{ $ringClass }}" />
        </span>
    </span>
@elseif ($useInitials)
    <span
        {{ $attributes->class([
            "inline-flex shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-blue-600 to-indigo-600 font-semibold text-white {$sizeClass} {$ringClass}",
        ]) }}
        title="{{ $name }}"
        aria-label="{{ $name }}">{{ $label }}</span>
@else
    <x-blog.media-placeholder type="avatar" :size="$size" :show-label="in_array($size, ['lg', 'xl'], true)"
        {{ $attributes->class([$ringClass]) }} />
@endif
