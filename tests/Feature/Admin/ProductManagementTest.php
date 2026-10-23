<?php

namespace Tests\Feature\Admin;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, mixed>
     */
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Cosecha Especial',
            'description' => 'Notas de panela y cacao.',
            'price' => 34900,
            'weight' => '500 g',
            'sort_order' => 5,
            'is_visible' => '1',
            'image' => UploadedFile::fake()->create('bolsa.png', 50, 'image/png'),
        ], $overrides);
    }

    public function test_guests_are_redirected_to_the_login(): void
    {
        $this->get(route('admin.products.index'))->assertRedirect(route('admin.login'));
        $this->post(route('admin.products.store'), [])->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_log_in_and_out(): void
    {
        $user = User::factory()->create();

        $this->post(route('admin.login'), ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user);

        $this->post(route('admin.logout'))->assertRedirect(route('admin.login'));
        $this->assertGuest();
    }

    public function test_login_fails_with_wrong_password(): void
    {
        $user = User::factory()->create();

        $this->post(route('admin.login'), ['email' => $user->email, 'password' => 'incorrecta'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_index_lists_products_including_hidden_ones(): void
    {
        Product::factory()->create(['name' => 'Visible uno']);
        Product::factory()->hidden()->create(['name' => 'Oculto dos']);

        $this->actingAs(User::factory()->create())
            ->get(route('admin.products.index'))
            ->assertOk()
            ->assertSee('Visible uno')
            ->assertSee('Oculto dos');
    }

    public function test_admin_can_create_a_product_with_a_photo(): void
    {
        Storage::fake('public');

        $this->actingAs(User::factory()->create())
            ->post(route('admin.products.store'), $this->validPayload())
            ->assertRedirect(route('admin.products.index'));

        $product = Product::firstWhere('name', 'Cosecha Especial');
        $this->assertNotNull($product);
        $this->assertSame(34900, $product->price);
        $this->assertTrue($product->is_visible);
        Storage::disk('public')->assertExists($product->image);
    }

    public function test_photo_is_required_when_creating(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('admin.products.store'), $this->validPayload(['image' => null]))
            ->assertSessionHasErrors('image');
    }

    public function test_non_image_uploads_are_rejected(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('admin.products.store'), $this->validPayload([
                'image' => UploadedFile::fake()->create('virus.pdf', 10, 'application/pdf'),
            ]))
            ->assertSessionHasErrors('image');

        $this->assertDatabaseCount('products', 0);
    }

    public function test_admin_can_update_a_product_without_replacing_the_photo(): void
    {
        $product = Product::factory()->create(['name' => 'Antiguo']);

        $this->actingAs(User::factory()->create())
            ->put(route('admin.products.update', $product), $this->validPayload(['name' => 'Nuevo nombre', 'image' => null, 'is_visible' => null]))
            ->assertRedirect(route('admin.products.index'));

        $product->refresh();
        $this->assertSame('Nuevo nombre', $product->name);
        $this->assertSame(Product::FALLBACK_IMAGE, $product->image);
        $this->assertFalse($product->is_visible);
    }

    public function test_replacing_the_photo_removes_the_previous_upload(): void
    {
        Storage::fake('public');
        $old = UploadedFile::fake()->create('vieja.png', 50, 'image/png')->store('productos', 'public');
        $product = Product::factory()->create(['image' => $old]);

        $this->actingAs(User::factory()->create())
            ->put(route('admin.products.update', $product), $this->validPayload());

        Storage::disk('public')->assertMissing($old);
        Storage::disk('public')->assertExists($product->refresh()->image);
    }

    public function test_admin_can_delete_a_product_and_its_photo(): void
    {
        Storage::fake('public');
        $path = UploadedFile::fake()->create('bolsa.png', 50, 'image/png')->store('productos', 'public');
        $product = Product::factory()->create(['image' => $path]);

        $this->actingAs(User::factory()->create())
            ->delete(route('admin.products.destroy', $product))
            ->assertRedirect(route('admin.products.index'));

        $this->assertModelMissing($product);
        Storage::disk('public')->assertMissing($path);
    }
}
