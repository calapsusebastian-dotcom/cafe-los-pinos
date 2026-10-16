<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PostRequest;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(): View
    {
        return view('admin.posts.index', [
            'posts' => Post::query()->latestFirst()->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.posts.create', [
            'post' => new Post(['is_published' => true, 'published_at' => now()]),
        ]);
    }

    public function store(PostRequest $request): RedirectResponse
    {
        Post::create($this->attributesFrom($request));

        return redirect()->route('admin.posts.index')->with('status', 'Artículo creado.');
    }

    public function edit(Post $post): View
    {
        return view('admin.posts.edit', compact('post'));
    }

    public function update(PostRequest $request, Post $post): RedirectResponse
    {
        $post->update($this->attributesFrom($request));

        return redirect()->route('admin.posts.index')->with('status', 'Artículo actualizado.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        $post->delete();

        return redirect()->route('admin.posts.index')->with('status', 'Artículo eliminado.');
    }

    /**
     * @return array<string, mixed>
     */
    private function attributesFrom(PostRequest $request): array
    {
        $data = $request->validated();
        $data['published_at'] ??= $data['is_published'] ? now() : null;

        return $data;
    }
}
