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
            ->orderByDesc('published_at')
            ->paginate(9);

        return view('blog.index', compact('posts'));
    }

    public function show(BlogPost $post): View
    {
        if (! $post->is_published || $post->published_at === null || $post->published_at->isFuture()) {
            abort(404);
        }

        return view('blog.show', compact('post'));
    }
}
