<?php

namespace App\Http\Requests\Admin;

use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PostRequest extends FormRequest
{
    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $post = $this->route('post');

        return [
            'title' => ['required', 'string', 'max:150'],
            'slug' => ['required', 'string', 'max:160', Rule::unique('posts', 'slug')->ignore($post instanceof Post ? $post->getKey() : null)],
            'excerpt' => ['required', 'string', 'max:300'],
            'body' => ['required', 'string', 'max:60000'],
            'is_published' => ['boolean'],
            'published_at' => ['nullable', 'date'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'title' => 'título',
            'slug' => 'dirección web',
            'excerpt' => 'resumen',
            'body' => 'contenido',
            'published_at' => 'fecha de publicación',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_published' => $this->boolean('is_published'),
            'slug' => Str::slug((string) ($this->input('slug') ?: $this->input('title'))),
        ]);
    }
}
