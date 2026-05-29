@props([
    'post',
    'avatarSize' => 'sm',
    'showTitle' => true,
])

@php
    /** @var \App\Models\BlogPost $post */
@endphp

<div {{ $attributes->class(['flex items-center gap-3']) }}>
    <x-blog.avatar :name="$post->displayAuthorName()" :url="$post->authorAvatarUrl()" :size="$avatarSize"
        fallback="placeholder" />
    <div class="min-w-0">
        <p class="truncate text-sm font-semibold text-slate-900">{{ $post->displayAuthorName() }}</p>
        @if ($showTitle && $post->displayAuthorTitle())
            <p class="truncate text-xs text-slate-500">{{ $post->displayAuthorTitle() }}</p>
        @endif
    </div>
</div>
