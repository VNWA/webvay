<x-layouts.app :title="$page->title">
    <article class="mx-auto max-w-3xl px-4 py-12 sm:px-6">
        <h1 class="text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">{{ $page->title }}</h1>
        <div class="cms-content mt-8 max-w-none space-y-4 text-base leading-relaxed text-slate-700 [&_a]:text-blue-600 [&_a]:underline [&_h1]:text-2xl [&_h1]:font-bold [&_h1]:text-slate-900 [&_h2]:mt-8 [&_h2]:text-xl [&_h2]:font-semibold [&_h2]:text-slate-900 [&_h3]:mt-6 [&_h3]:text-lg [&_h3]:font-semibold [&_li]:my-1 [&_ol]:list-decimal [&_ol]:pl-6 [&_p]:my-3 [&_ul]:list-disc [&_ul]:pl-6">
            {!! $page->body !!}
        </div>
    </article>
</x-layouts.app>
