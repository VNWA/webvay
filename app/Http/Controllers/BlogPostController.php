<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Contracts\View\View;

class BlogPostController extends Controller
{
    public function index(): View
    {
        $posts = BlogPost::query()
            ->published()
            ->with('author')
            ->orderByDesc('published_at')
            ->paginate(9);

        return view('blog.index', compact('posts'));
    }

    public function show(BlogPost $post): View
    {
        if (! $post->is_published || $post->published_at === null || $post->published_at->isFuture()) {
            abort(404);
        }

        $post->load('author');

        $related = BlogPost::query()
            ->published()
            ->with('author')
            ->where('id', '!=', $post->id)
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        return view('blog.show', compact('post', 'related'));
    }
}
