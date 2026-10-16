<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\SiteSetting;
use App\Models\Subscriber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreFrontTest extends TestCase
{
    use RefreshDatabase;

    public function test_story_page_shows_the_default_chapters(): void
    {
        $this->get(route('story'))
            ->assertOk()
            ->assertSee('Nacimos entre montañas')
            ->assertSee('Caficultores que cuidan cada grano')
            ->assertSee('Origen');
    }

    public function test_story_page_uses_the_texts_saved_by_the_admin(): void
    {
        SiteSetting::create(['key' => 'chapter_1_title', 'value' => 'Un capítulo editado']);

        $this->get(route('story'))
            ->assertSee('Un capítulo editado')
            ->assertDontSee('Nacimos entre montañas');
    }

    public function test_home_links_to_the_story_page(): void
    {
        $this->get(route('home'))->assertSee(route('story'), false);
    }

    public function test_search_filters_products_by_name_or_description(): void
    {
        Product::factory()->create(['name' => 'Clásico Huila', 'description' => 'Suave y dulce']);
        Product::factory()->create(['name' => 'Premium', 'description' => 'Notas de chocolate']);

        $this->get(route('home', ['q' => 'clásico']))
            ->assertOk()
            ->assertSee('Clásico Huila')
            ->assertDontSee('Premium');

        $this->get(route('home', ['q' => 'chocolate']))
            ->assertSee('Premium')
            ->assertDontSee('Clásico Huila');
    }

    public function test_search_without_results_shows_a_message(): void
    {
        Product::factory()->create(['name' => 'Clásico']);

        $this->get(route('home', ['q' => 'zzz']))
            ->assertOk()
            ->assertSee('No encontramos productos para')
            ->assertDontSee('Clásico');
    }

    public function test_search_treats_wildcards_as_plain_text(): void
    {
        Product::factory()->create(['name' => 'Clásico']);

        $this->get(route('home', ['q' => '%']))->assertDontSee('Clásico');
    }

    public function test_product_page_shows_the_product_and_structured_data(): void
    {
        $product = Product::factory()->create(['name' => 'Reserva', 'price' => 45000]);

        $this->get(route('product', $product))
            ->assertOk()
            ->assertSee('Reserva')
            ->assertSee('45.000')
            ->assertSee('"@type":"Product"', false)
            ->assertSee('data-add-to-cart', false);
    }

    public function test_hidden_products_have_no_public_page(): void
    {
        $product = Product::factory()->hidden()->create();

        $this->get(route('product', $product))->assertNotFound();
    }

    public function test_home_cards_link_to_the_product_page(): void
    {
        $product = Product::factory()->create();

        $this->get(route('home'))
            ->assertSee(route('product', $product), false)
            ->assertSee('data-price="'.$product->price.'"', false);
    }

    public function test_pages_have_seo_tags(): void
    {
        $this->get(route('home'))
            ->assertSee('<meta name="description"', false)
            ->assertSee('<link rel="canonical"', false)
            ->assertSee('property="og:title"', false)
            ->assertSee('"@type":"Store"', false);
    }

    public function test_sitemap_lists_public_pages_and_visible_products_only(): void
    {
        $visible = Product::factory()->create();
        $hidden = Product::factory()->hidden()->create();

        $this->get(route('sitemap'))
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml')
            ->assertSee(route('home'), false)
            ->assertSee(route('story'), false)
            ->assertSee(route('product', $visible), false)
            ->assertDontSee(route('product', $hidden), false);
    }

    public function test_visitors_can_subscribe_to_the_newsletter(): void
    {
        $this->from(route('home'))
            ->post(route('subscribe'), ['email' => '  Persona@Correo.COM '])
            ->assertRedirect()
            ->assertSessionHas('newsletter_status');

        $this->assertDatabaseHas('subscribers', ['email' => 'persona@correo.com']);
    }

    public function test_subscribing_twice_does_not_duplicate_the_email(): void
    {
        $this->post(route('subscribe'), ['email' => 'repetido@correo.com']);
        $this->post(route('subscribe'), ['email' => 'repetido@correo.com']);

        $this->assertDatabaseCount('subscribers', 1);
    }

    public function test_subscribing_requires_a_valid_email(): void
    {
        $this->from(route('home'))
            ->post(route('subscribe'), ['email' => 'no-es-un-correo'])
            ->assertSessionHasErrors('email');

        $this->assertDatabaseCount('subscribers', 0);
    }

    public function test_subscribing_is_rate_limited(): void
    {
        foreach (range(1, 5) as $attempt) {
            $this->post(route('subscribe'), ['email' => "persona{$attempt}@correo.com"]);
        }

        $this->post(route('subscribe'), ['email' => 'otra@correo.com'])->assertTooManyRequests();
        $this->assertSame(5, Subscriber::count());
    }

    public function test_the_cart_drawer_is_present_with_the_whatsapp_number(): void
    {
        $this->get(route('home'))
            ->assertSee('id="carrito"', false)
            ->assertSee('data-whatsapp="https://wa.me/573208332844"', false);
    }

    public function test_robots_txt_points_to_the_absolute_sitemap_url(): void
    {
        $this->get(route('robots'))
            ->assertOk()
            ->assertSee('Disallow: /admin')
            ->assertSee('Sitemap: '.route('sitemap'), false);
    }

    public function test_search_results_are_not_indexed_but_the_home_is(): void
    {
        $this->get(route('home'))->assertSee('index, follow', false);

        $this->get(route('home', ['q' => 'huila']))
            ->assertSee('noindex, follow', false)
            ->assertSee('<link rel="canonical" href="'.route('home').'">', false);
    }

    public function test_product_page_exposes_structured_data(): void
    {
        $product = Product::factory()->create(['name' => 'Clásico Huila']);

        $this->get(route('product', $product))
            ->assertSee('"@type":"Product"', false)
            ->assertSee('"@type":"BreadcrumbList"', false)
            ->assertSee('"sku":"CLP-'.$product->id.'"', false);
    }
}
