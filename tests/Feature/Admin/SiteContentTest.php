<?php

namespace Tests\Feature\Admin;

use App\Models\SiteSetting;
use App\Models\User;
use App\Support\SiteContent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SiteContentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, string>
     */
    private function allDefaults(): array
    {
        return collect(SiteContent::fields())
            ->map(fn (array $field): string => $field['default'])
            ->all();
    }

    public function test_guests_cannot_edit_the_content(): void
    {
        $this->get(route('admin.content.edit'))->assertRedirect(route('admin.login'));
        $this->put(route('admin.content.update'), $this->allDefaults())->assertRedirect(route('admin.login'));
    }

    public function test_edit_form_shows_the_default_texts(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('admin.content.edit'))
            ->assertOk()
            ->assertSee('El verdadero sabor del Huila en cada taza');
    }

    public function test_admin_can_change_the_texts(): void
    {
        $payload = ['hero_title' => 'Un título nuevo'] + $this->allDefaults();

        $this->actingAs(User::factory()->create())
            ->put(route('admin.content.update'), $payload)
            ->assertRedirect(route('admin.content.edit'));

        $this->assertSame('Un título nuevo', SiteContent::all()['hero_title']);
    }

    public function test_every_field_is_required(): void
    {
        $payload = ['hero_title' => ''] + $this->allDefaults();

        $this->actingAs(User::factory()->create())
            ->put(route('admin.content.update'), $payload)
            ->assertSessionHasErrors('hero_title');
    }

    public function test_social_links_must_be_http_urls_or_a_hash(): void
    {
        $payload = ['instagram_url' => 'javascript:alert(1)'] + $this->allDefaults();

        $this->actingAs(User::factory()->create())
            ->put(route('admin.content.update'), $payload)
            ->assertSessionHasErrors('instagram_url');
    }

    public function test_whatsapp_number_must_be_digits_only(): void
    {
        $payload = ['whatsapp_number' => '+57 320 833 2844'] + $this->allDefaults();

        $this->actingAs(User::factory()->create())
            ->put(route('admin.content.update'), $payload)
            ->assertSessionHasErrors('whatsapp_number');
    }

    public function test_admin_can_upload_and_remove_a_site_image(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create();

        $this->actingAs($admin)
            ->put(route('admin.content.update'), ['hero_image' => UploadedFile::fake()->create('portada.png', 50, 'image/png')] + $this->allDefaults())
            ->assertSessionHasNoErrors();

        $path = SiteSetting::query()->where('key', 'hero_image')->value('value');
        Storage::disk('public')->assertExists($path);
        $this->assertStringContainsString($path, SiteContent::imageUrls()['hero_image']);
        $this->get(route('home'))->assertSee($path, false);

        $this->actingAs($admin)
            ->put(route('admin.content.update'), ['remove_hero_image' => '1'] + $this->allDefaults())
            ->assertSessionHasNoErrors();

        Storage::disk('public')->assertMissing($path);
        $this->assertStringEndsWith('img/finca.png', SiteContent::imageUrls()['hero_image']);
    }

    public function test_site_images_must_be_valid_pictures(): void
    {
        $payload = ['hero_image' => UploadedFile::fake()->create('portada.pdf', 10, 'application/pdf')] + $this->allDefaults();

        $this->actingAs(User::factory()->create())
            ->put(route('admin.content.update'), $payload)
            ->assertSessionHasErrors('hero_image');
    }

    public function test_unknown_keys_are_not_stored(): void
    {
        SiteContent::save(['clave_inventada' => 'x']);

        $this->assertDatabaseCount('site_settings', 0);
    }
}
