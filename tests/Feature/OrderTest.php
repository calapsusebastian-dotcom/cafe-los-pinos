<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, mixed>
     */
    private function payload(array $items, array $overrides = []): array
    {
        return array_merge([
            'customer_name' => 'María Pérez',
            'customer_phone' => '300 123 4567',
            'city' => 'Neiva',
            'address' => 'Calle 10 # 5-20',
            'notes' => 'Molido para filtro',
            'items' => $items,
        ], $overrides);
    }

    public function test_an_order_is_saved_with_the_prices_stored_in_the_database(): void
    {
        $classic = Product::factory()->create(['name' => 'Clásico', 'price' => 20000]);
        $premium = Product::factory()->create(['name' => 'Premium', 'price' => 35000]);

        $response = $this->postJson(route('orders.store'), $this->payload([
            ['id' => $classic->id, 'qty' => 2],
            ['id' => $premium->id, 'qty' => 1],
        ]));

        $response->assertCreated()->assertJsonStructure(['order', 'whatsapp_url']);

        $order = Order::with('items')->firstOrFail();
        $this->assertSame(75000, $order->subtotal);
        $this->assertSame(75000, $order->total);
        $this->assertSame('3001234567', $order->customer_phone);
        $this->assertSame(OrderStatus::Pending, $order->status);
        $this->assertCount(2, $order->items);
        $this->assertSame(20000, $order->items->firstWhere('name', 'Clásico')->unit_price);
    }

    public function test_the_whatsapp_link_summarises_the_order(): void
    {
        $product = Product::factory()->create(['name' => 'Clásico', 'price' => 20000]);

        $url = $this->postJson(route('orders.store'), $this->payload([['id' => $product->id, 'qty' => 1]]))
            ->json('whatsapp_url');

        $this->assertStringStartsWith('https://wa.me/573208332844?text=', $url);
        $message = urldecode(substr($url, strlen('https://wa.me/573208332844?text=')));
        $this->assertStringContainsString('pedido #'.Order::first()->id, $message);
        $this->assertStringContainsString('1 x Café Clásico', $message);
        $this->assertStringContainsString('Neiva', $message);
    }

    public function test_repeated_products_are_merged(): void
    {
        $product = Product::factory()->create(['price' => 10000]);

        $this->postJson(route('orders.store'), $this->payload([
            ['id' => $product->id, 'qty' => 1],
            ['id' => $product->id, 'qty' => 2],
        ]))->assertCreated();

        $order = Order::with('items')->firstOrFail();
        $this->assertCount(1, $order->items);
        $this->assertSame(3, $order->items->first()->quantity);
        $this->assertSame(30000, $order->subtotal);
    }

    public function test_shipping_is_added_and_can_be_free_above_a_threshold(): void
    {
        SiteSetting::create(['key' => 'shipping_cost', 'value' => '12000']);
        SiteSetting::create(['key' => 'free_shipping_from', 'value' => '100000']);
        $product = Product::factory()->create(['price' => 30000]);

        $this->postJson(route('orders.store'), $this->payload([['id' => $product->id, 'qty' => 1]]))->assertCreated();
        $this->assertSame(12000, Order::latest('id')->first()->shipping_cost);
        $this->assertSame(42000, Order::latest('id')->first()->total);

        $this->postJson(route('orders.store'), $this->payload([['id' => $product->id, 'qty' => 4]]))->assertCreated();
        $this->assertSame(0, Order::latest('id')->first()->shipping_cost);
        $this->assertSame(120000, Order::latest('id')->first()->total);
    }

    public function test_hidden_or_unknown_products_are_rejected(): void
    {
        $hidden = Product::factory()->hidden()->create();

        $this->postJson(route('orders.store'), $this->payload([['id' => $hidden->id, 'qty' => 1]]))
            ->assertUnprocessable()
            ->assertJsonValidationErrors('items.0.id');
        $this->postJson(route('orders.store'), $this->payload([['id' => 9999, 'qty' => 1]]))
            ->assertUnprocessable();

        $this->assertDatabaseCount('orders', 0);
    }

    public function test_customer_data_and_items_are_required(): void
    {
        $this->postJson(route('orders.store'), [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['customer_name', 'customer_phone', 'city', 'address', 'items']);
    }

    public function test_the_phone_must_be_a_valid_number(): void
    {
        $product = Product::factory()->create();

        $this->postJson(route('orders.store'), $this->payload([['id' => $product->id, 'qty' => 1]], ['customer_phone' => '123']))
            ->assertUnprocessable()
            ->assertJsonValidationErrors('customer_phone');
    }

    public function test_quantities_must_be_reasonable(): void
    {
        $product = Product::factory()->create();

        $this->postJson(route('orders.store'), $this->payload([['id' => $product->id, 'qty' => 0]]))
            ->assertUnprocessable();
        $this->postJson(route('orders.store'), $this->payload([['id' => $product->id, 'qty' => 500]]))
            ->assertUnprocessable();
    }

    public function test_the_cart_drawer_renders_the_checkout_form(): void
    {
        $this->get(route('home'))
            ->assertSee('data-orders-url="'.route('orders.store').'"', false)
            ->assertSee('name="customer_name"', false)
            ->assertSee('name="csrf-token"', false);
    }
}
