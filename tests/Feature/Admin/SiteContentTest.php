<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Support\SiteContent;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

    public function test_unknown_keys_are_not_stored(): void
    {
        SiteContent::save(['clave_inventada' => 'x']);

        $this->assertDatabaseCount('site_settings', 0);
    }
}
