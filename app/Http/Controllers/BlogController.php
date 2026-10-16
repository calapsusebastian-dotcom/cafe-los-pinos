<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Support\SiteContent;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        return view('blog.index', [
            'posts' => Post::query()->published()->latestFirst()->paginate(9),
            'content' => SiteContent::all(),
        ]);
    }

    public function show(Post $post): View
    {
        abort_unless($post->isLive(), 404);

        return view('blog.show', [
            'post' => $post,
            'more' => Post::query()->published()->whereKeyNot($post->getKey())->latestFirst()->limit(3)->get(),
            'content' => SiteContent::all(),
        ]);
    }
}
