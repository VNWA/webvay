<x-layouts.app :title="__('nav_news')">
    <div class="mx-auto max-w-6xl px-4 py-12 sm:px-6">
        <div class="max-w-2xl">
            <h1 class="text-3xl font-bold tracking-tight text-slate-900">{{ __('nav_news') }}</h1>
            <p class="mt-2 text-slate-600">{{ __('blog_index_subtitle') }}</p>
        </div>

        @php
            $rest = $posts->slice(1);
        @endphp

        <div class="mt-10 space-y-8">
            @forelse ($posts as $post)
                @if ($loop->first)
                    <x-blog.card :post="$post" featured class="col-span-full" />
                @endif
            @empty
                <p class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 px-6 py-12 text-center text-slate-600">
                    {{ __('blog_no_posts') }}
                </p>
            @endforelse

            @if ($rest->isNotEmpty())
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($rest as $post)
                        <x-blog.card :post="$post" />
                    @endforeach
                </div>
            @endif
        </div>

        @if ($posts->hasPages())
            <div class="mt-10">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
