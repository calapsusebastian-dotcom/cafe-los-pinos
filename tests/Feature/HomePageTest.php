<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_shows_only_visible_products_in_order(): void
    {
        Product::factory()->create(['name' => 'Segundo', 'sort_order' => 2]);
        Product::factory()->create(['name' => 'Primero', 'sort_order' => 1]);
        Product::factory()->hidden()->create(['name' => 'Escondido']);

        $this->get(route('home'))
            ->assertOk()
            ->assertSeeInOrder(['Primero', 'Segundo'])
            ->assertDontSee('Escondido');
    }

    public function test_home_uses_defaults_and_then_saved_texts(): void
    {
        $this->get(route('home'))->assertSee('El verdadero sabor del Huila en cada taza');

        SiteSetting::create(['key' => 'hero_title', 'value' => 'Título editado']);

        $this->get(route('home'))
            ->assertSee('Título editado')
            ->assertDontSee('El verdadero sabor del Huila en cada taza');
    }

    public function test_home_escapes_texts_saved_by_the_admin(): void
    {
        SiteSetting::create(['key' => 'hero_title', 'value' => '<script>alert(1)</script>']);

        $this->get(route('home'))->assertDontSee('<script>alert(1)</script>', false);
    }

    public function test_home_has_a_whatsapp_button_for_the_configured_number(): void
    {
        $this->get(route('home'))
            ->assertSee('https://wa.me/573208332844?text=', false)
            ->assertSee('whatsapp-float', false);

        SiteSetting::create(['key' => 'whatsapp_number', 'value' => '573001112233']);

        $this->get(route('home'))->assertSee('https://wa.me/573001112233?text=', false);
    }

    public function test_hero_buy_button_opens_whatsapp(): void
    {
        $this->get(route('home'))
            ->assertSee('<a href="https://wa.me/573208332844?text=', false)
            ->assertSeeInOrder(['hero__text', 'wa.me', 'Comprar ahora'], false);
    }

    public function test_home_shows_a_message_when_there_are_no_products(): void
    {
        $this->get(route('home'))->assertSee('Pronto tendremos nuevos productos');
    }
}
