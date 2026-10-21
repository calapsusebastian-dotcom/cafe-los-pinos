<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PostRequest;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
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
        $data = $this->attributesFrom($request);

        if ($request->hasFile('cover_image') || $request->boolean('remove_cover_image')) {
            $this->deleteCover($post);
            $data['cover_image'] ??= null;
        }

        $post->update($data);

        return redirect()->route('admin.posts.index')->with('status', 'Artículo actualizado.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        $this->deleteCover($post);
        $post->delete();

        return redirect()->route('admin.posts.index')->with('status', 'Artículo eliminado.');
    }

    /**
     * @return array<string, mixed>
     */
    private function attributesFrom(PostRequest $request): array
    {
        $data = $request->safe()->except(['cover_image', 'remove_cover_image']);
        $data['published_at'] ??= $data['is_published'] ? now() : null;

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('blog', 'public');
        }

        return $data;
    }

    private function deleteCover(Post $post): void
    {
        if (filled($post->cover_image)) {
            Storage::disk('public')->delete($post->cover_image);
        }
    }
}
