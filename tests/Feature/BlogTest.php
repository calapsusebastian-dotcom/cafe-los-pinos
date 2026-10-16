<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogTest extends TestCase
{
    use RefreshDatabase;

    public function test_blog_index_lists_only_live_posts(): void
    {
        Post::factory()->create(['title' => 'Guía publicada']);
        Post::factory()->draft()->create(['title' => 'Borrador oculto']);
        Post::factory()->scheduled()->create(['title' => 'Artículo programado']);

        $this->get(route('blog.index'))
            ->assertOk()
            ->assertSee('Guía publicada')
            ->assertDontSee('Borrador oculto')
            ->assertDontSee('Artículo programado');
    }

    public function test_post_page_renders_markdown_and_structured_data(): void
    {
        $post = Post::factory()->create(['title' => 'Cómo moler', 'body' => "## Subtítulo\n\nTexto con **negrita**."]);

        $this->get(route('blog.show', $post))
            ->assertOk()
            ->assertSee('<h2>Subtítulo</h2>', false)
            ->assertSee('<strong>negrita</strong>', false)
            ->assertSee('"@type":"BlogPosting"', false)
            ->assertSee('"@type":"BreadcrumbList"', false);
    }

    public function test_post_body_strips_raw_html(): void
    {
        $post = Post::factory()->create(['body' => '<script>alert(1)</script>Hola']);

        $this->get(route('blog.show', $post))->assertDontSee('<script>alert(1)</script>', false);
    }

    public function test_unpublished_and_scheduled_posts_return_404(): void
    {
        $draft = Post::factory()->draft()->create();
        $scheduled = Post::factory()->scheduled()->create();

        $this->get(route('blog.show', $draft))->assertNotFound();
        $this->get(route('blog.show', $scheduled))->assertNotFound();
    }

    public function test_sitemap_includes_live_posts_only(): void
    {
        $live = Post::factory()->create();
        $draft = Post::factory()->draft()->create();

        $this->get(route('sitemap'))
            ->assertSee(route('blog.index'), false)
            ->assertSee(route('blog.show', $live), false)
            ->assertDontSee(route('blog.show', $draft), false);
    }
}
