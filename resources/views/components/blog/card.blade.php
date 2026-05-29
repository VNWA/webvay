@props([
    'post',
    'featured' => false,
])

@php
    /** @var \App\Models\BlogPost $post */
@endphp

<a href="{{ route('blog.show', $post) }}"
    {{ $attributes->class([
        'group flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:border-blue-200 hover:shadow-md',
        'lg:flex-row' => $featured,
    ]) }}>
    <x-blog.cover :url="$post->coverImageUrl()" :featured="$featured" />

    <div @class(['flex flex-1 flex-col p-5 sm:p-6', 'lg:justify-center' => $featured])>
        <div class="flex flex-wrap items-center gap-2 text-xs font-medium text-slate-500">
            <time datetime="{{ $post->published_at?->toAtomString() }}">
                {{ $post->published_at?->format('d/m/Y') }}
            </time>
            <span aria-hidden="true">·</span>
            <span>{{ __('blog_reading_time', ['min' => $post->readingTimeMinutes()]) }}</span>
        </div>

        <h2 @class([
            'mt-2 font-semibold text-slate-900 group-hover:text-blue-700',
            'text-lg' => ! $featured,
            'text-xl sm:text-2xl' => $featured,
        ])>{{ $post->title }}</h2>

        @if ($post->excerpt)
            <p @class([
                'mt-2 text-slate-600',
                'line-clamp-2 text-sm' => ! $featured,
                'line-clamp-3 text-sm sm:text-base' => $featured,
            ])>{{ $post->excerpt }}</p>
        @endif

        <div class="mt-auto flex items-center justify-between gap-4 pt-4">
            <x-blog.author :post="$post" avatar-size="sm" />
            <span class="shrink-0 text-sm font-medium text-blue-600">{{ __('blog_read_more') }} →</span>
        </div>
    </div>
</a>
