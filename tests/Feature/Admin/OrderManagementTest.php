<?php

namespace Tests\Feature\Admin;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_login(): void
    {
        $order = Order::factory()->create();

        $this->get(route('admin.orders.index'))->assertRedirect(route('admin.login'));
        $this->get(route('admin.orders.show', $order))->assertRedirect(route('admin.login'));
        $this->put(route('admin.orders.update', $order), ['status' => 'shipped'])->assertRedirect(route('admin.login'));
    }

    public function test_admin_sees_the_orders_and_can_filter_by_status(): void
    {
        Order::factory()->create(['customer_name' => 'Cliente Pendiente']);
        Order::factory()->create(['customer_name' => 'Cliente Enviado', 'status' => OrderStatus::Shipped]);

        $this->actingAs(User::factory()->create())
            ->get(route('admin.orders.index'))
            ->assertOk()
            ->assertSee('Cliente Pendiente')
            ->assertSee('Cliente Enviado');

        $this->actingAs(User::factory()->create())
            ->get(route('admin.orders.index', ['estado' => 'shipped']))
            ->assertSee('Cliente Enviado')
            ->assertDontSee('Cliente Pendiente');
    }

    public function test_admin_can_view_an_order_with_its_items(): void
    {
        $order = Order::factory()->create(['customer_name' => 'Ana Gómez']);
        $order->items()->create(['name' => 'Clásico', 'weight' => '500 g', 'unit_price' => 20000, 'quantity' => 2]);

        $this->actingAs(User::factory()->create())
            ->get(route('admin.orders.show', $order))
            ->assertOk()
            ->assertSee('Ana Gómez')
            ->assertSee('Clásico')
            ->assertSee('40.000');
    }

    public function test_admin_can_change_the_status(): void
    {
        $order = Order::factory()->create();

        $this->actingAs(User::factory()->create())
            ->put(route('admin.orders.update', $order), ['status' => 'shipped'])
            ->assertRedirect(route('admin.orders.show', $order));

        $this->assertSame(OrderStatus::Shipped, $order->fresh()->status);
    }

    public function test_an_invalid_status_is_rejected(): void
    {
        $order = Order::factory()->create();

        $this->actingAs(User::factory()->create())
            ->put(route('admin.orders.update', $order), ['status' => 'inventado'])
            ->assertSessionHasErrors('status');
    }
}
