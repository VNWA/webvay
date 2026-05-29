@props([
    'url' => null,
    'featured' => false,
    'alt' => '',
])

@php
    $frameClass = $featured
        ? 'aspect-[16/10] w-full lg:aspect-auto lg:w-2/5 lg:min-h-[220px]'
        : 'aspect-[16/10] w-full';
    $heroClass = 'relative h-56 w-full overflow-hidden sm:h-72 lg:h-80';
    $isHero = $attributes->has('hero');
    $uid = 'cv-' . substr(md5(($url ?? '') . ($featured ? '1' : '0')), 0, 8);
@endphp

@if ($url)
    <div @class([
        'relative shrink-0 overflow-hidden bg-slate-100',
        $frameClass => ! $isHero,
        $heroClass => $isHero,
    ]) {{ $attributes->except('hero') }}>
        <img src="{{ $url }}" alt="{{ $alt }}"
            id="{{ $uid }}-img"
            @class([
                'h-full w-full object-cover',
                'transition duration-500 group-hover:scale-[1.02]' => ! $isHero,
            ])
            @if ($isHero) fetchpriority="high" @else loading="lazy" decoding="async" @endif
            onerror="this.classList.add('hidden'); document.getElementById('{{ $uid }}-ph')?.classList.remove('hidden');">
        <div id="{{ $uid }}-ph" @class(['absolute inset-0 hidden', 'flex' => $isHero])>
            <x-blog.media-placeholder type="cover" class="h-full w-full rounded-none" />
        </div>
        @if ($isHero)
            <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-slate-900/50 to-transparent"></div>
        @endif
    </div>
@else
    <div @class([
        'relative shrink-0 overflow-hidden',
        $frameClass => ! $isHero,
        $heroClass => $isHero,
    ]) {{ $attributes->except('hero') }}>
        <x-blog.media-placeholder type="cover" class="h-full w-full rounded-none" />
        @if ($isHero)
            <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-slate-900/20 to-transparent"></div>
        @endif
    </div>
@endif
