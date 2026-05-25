<x-layouts.app :title="__('nav_news')">
    <div class="mx-auto max-w-6xl px-4 py-12 sm:px-6">
        <h1 class="text-3xl font-bold tracking-tight text-slate-900">{{ __('nav_news') }}</h1>
        <p class="mt-2 text-slate-600">{{ __('blog_index_subtitle') }}</p>

        <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($posts as $post)
                <a href="{{ route('blog.show', $post) }}"
                    class="group flex flex-col rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:border-blue-200 hover:shadow-md">
                    <time class="text-xs font-medium text-slate-500" datetime="{{ $post->published_at?->toAtomString() }}">
                        {{ $post->published_at?->format('d/m/Y H:i') }}
                    </time>
                    <h2 class="mt-2 text-lg font-semibold text-slate-900 group-hover:text-blue-700">{{ $post->title }}</h2>
                    @if ($post->excerpt)
                        <p class="mt-2 line-clamp-3 flex-1 text-sm text-slate-600">{{ $post->excerpt }}</p>
                    @endif
                    <span class="mt-4 text-sm font-medium text-blue-600">{{ __('blog_read_more') }} →</span>
                </a>
            @empty
                <p class="col-span-full rounded-2xl border border-dashed border-slate-200 bg-slate-50 px-6 py-12 text-center text-slate-600">
                    {{ __('blog_no_posts') }}
                </p>
            @endforelse
        </div>

        @if ($posts->hasPages())
            <div class="mt-10">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
