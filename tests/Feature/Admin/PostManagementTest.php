<?php

namespace Tests\Feature\Admin;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, mixed>
     */
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'title' => 'Cómo elegir tu café',
            'slug' => '',
            'excerpt' => 'Consejos para escoger el café ideal.',
            'body' => "## Empieza aquí\n\nTexto del artículo.",
            'is_published' => '1',
            'published_at' => '',
        ], $overrides);
    }

    public function test_guests_are_redirected_to_the_login(): void
    {
        $this->get(route('admin.posts.index'))->assertRedirect(route('admin.login'));
        $this->post(route('admin.posts.store'), [])->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_create_a_post_with_an_automatic_slug_and_date(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('admin.posts.store'), $this->validPayload())
            ->assertRedirect(route('admin.posts.index'));

        $post = Post::firstOrFail();
        $this->assertSame('como-elegir-tu-cafe', $post->slug);
        $this->assertTrue($post->isLive());
    }

    public function test_a_draft_is_saved_without_a_publication_date(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('admin.posts.store'), $this->validPayload(['is_published' => '0']));

        $post = Post::firstOrFail();
        $this->assertFalse($post->is_published);
        $this->assertNull($post->published_at);
    }

    public function test_slug_must_be_unique(): void
    {
        Post::factory()->create(['slug' => 'como-elegir-tu-cafe']);

        $this->actingAs(User::factory()->create())
            ->post(route('admin.posts.store'), $this->validPayload())
            ->assertSessionHasErrors('slug');
    }

    public function test_required_fields_are_validated(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('admin.posts.store'), [])
            ->assertSessionHasErrors(['title', 'excerpt', 'body']);
    }

    public function test_admin_can_update_a_post_keeping_its_own_slug(): void
    {
        $post = Post::factory()->create(['slug' => 'mi-articulo']);

        $this->actingAs(User::factory()->create())
            ->put(route('admin.posts.update', $post), $this->validPayload(['title' => 'Título nuevo', 'slug' => 'mi-articulo']))
            ->assertRedirect(route('admin.posts.index'));

        $this->assertSame('Título nuevo', $post->fresh()->title);
    }

    public function test_admin_can_delete_a_post(): void
    {
        $post = Post::factory()->create();

        $this->actingAs(User::factory()->create())
            ->delete(route('admin.posts.destroy', $post))
            ->assertRedirect(route('admin.posts.index'));

        $this->assertModelMissing($post);
    }
}
