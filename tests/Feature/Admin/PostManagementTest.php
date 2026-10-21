<?php

namespace Tests\Feature\Admin;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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

    public function test_admin_can_upload_replace_and_remove_a_cover_image(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create();

        $this->actingAs($admin)
            ->post(route('admin.posts.store'), $this->validPayload(['cover_image' => UploadedFile::fake()->create('portada.png', 50, 'image/png')]))
            ->assertSessionHasNoErrors();

        $post = Post::firstOrFail();
        Storage::disk('public')->assertExists($post->cover_image);
        $this->get(route('blog.show', $post))->assertSee($post->cover_url, false);
        $this->get(route('blog.index'))->assertSee($post->cover_url, false);

        $first = $post->cover_image;
        $this->actingAs($admin)
            ->put(route('admin.posts.update', $post), $this->validPayload(['slug' => $post->slug, 'cover_image' => UploadedFile::fake()->create('otra.png', 50, 'image/png')]));

        $second = $post->fresh()->cover_image;
        $this->assertNotSame($first, $second);
        Storage::disk('public')->assertMissing($first);
        Storage::disk('public')->assertExists($second);

        $this->actingAs($admin)
            ->put(route('admin.posts.update', $post), $this->validPayload(['slug' => $post->slug, 'remove_cover_image' => '1']));

        $this->assertNull($post->fresh()->cover_image);
        Storage::disk('public')->assertMissing($second);
    }

    public function test_updating_without_a_new_file_keeps_the_cover_image(): void
    {
        Storage::fake('public');
        $path = UploadedFile::fake()->create('portada.png', 50, 'image/png')->store('blog', 'public');
        $post = Post::factory()->create(['slug' => 'mi-articulo', 'cover_image' => $path]);

        $this->actingAs(User::factory()->create())
            ->put(route('admin.posts.update', $post), $this->validPayload(['slug' => 'mi-articulo']));

        $this->assertSame($path, $post->fresh()->cover_image);
        Storage::disk('public')->assertExists($path);
    }

    public function test_cover_image_must_be_a_picture(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('admin.posts.store'), $this->validPayload(['cover_image' => UploadedFile::fake()->create('virus.pdf', 10, 'application/pdf')]))
            ->assertSessionHasErrors('cover_image');
    }

    public function test_deleting_a_post_deletes_its_cover_image(): void
    {
        Storage::fake('public');
        $path = UploadedFile::fake()->create('portada.png', 50, 'image/png')->store('blog', 'public');
        $post = Post::factory()->create(['cover_image' => $path]);

        $this->actingAs(User::factory()->create())->delete(route('admin.posts.destroy', $post));

        Storage::disk('public')->assertMissing($path);
    }
}
