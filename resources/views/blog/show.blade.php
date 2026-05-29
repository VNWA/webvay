<x-layouts.app :title="$post->title">
    <article>
        <x-blog.cover :url="$post->coverImageUrl()" hero />

        <div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 sm:py-12">
            <p class="text-sm font-medium text-slate-500">
                <a href="{{ route('blog.index') }}" class="text-blue-600 hover:underline">{{ __('nav_news') }}</a>
                <span class="mx-2">/</span>
                <time datetime="{{ $post->published_at?->toAtomString() }}">{{ $post->published_at?->format('d/m/Y H:i') }}</time>
                <span class="mx-2">·</span>
                <span>{{ __('blog_reading_time', ['min' => $post->readingTimeMinutes()]) }}</span>
            </p>

            <h1 class="mt-3 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">{{ $post->title }}</h1>

            @if ($post->excerpt)
                <p class="mt-4 text-lg text-slate-600">{{ $post->excerpt }}</p>
            @endif

            <div class="mt-8 flex items-center gap-4 rounded-2xl border border-slate-200 bg-slate-50/80 p-4 sm:p-5">
                <x-blog.avatar :name="$post->displayAuthorName()" :url="$post->authorAvatarUrl()" size="lg" ring />
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">{{ __('blog_written_by') }}</p>
                    <p class="text-lg font-semibold text-slate-900">{{ $post->displayAuthorName() }}</p>
                    @if ($post->displayAuthorTitle())
                        <p class="text-sm text-slate-600">{{ $post->displayAuthorTitle() }}</p>
                    @elseif (! $post->authorAvatarUrl())
                        <p class="text-xs text-slate-500">{{ __('blog_author_no_photo') }}</p>
                    @endif
                </div>
            </div>

            <div class="cms-content mt-10 max-w-none space-y-4 text-base leading-relaxed text-slate-700 [&_a]:text-blue-600 [&_a]:underline [&_h1]:text-2xl [&_h1]:font-bold [&_h1]:text-slate-900 [&_h2]:mt-8 [&_h2]:text-xl [&_h2]:font-semibold [&_h2]:text-slate-900 [&_h3]:mt-6 [&_h3]:text-lg [&_h3]:font-semibold [&_li]:my-1 [&_ol]:list-decimal [&_ol]:pl-6 [&_p]:my-3 [&_ul]:list-disc [&_ul]:pl-6">
                {!! $post->body !!}
            </div>
        </div>

        @if ($related->isNotEmpty())
            <aside class="border-t border-slate-200 bg-slate-50/50 py-12">
                <div class="mx-auto max-w-6xl px-4 sm:px-6">
                    <h2 class="text-xl font-bold text-slate-900">{{ __('blog_related_posts') }}</h2>
                    <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($related as $relatedPost)
                            <x-blog.card :post="$relatedPost" />
                        @endforeach
                    </div>
                </div>
            </aside>
        @endif
    </article>
</x-layouts.app>
