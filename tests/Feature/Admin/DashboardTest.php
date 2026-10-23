<?php

namespace Tests\Feature\Admin;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Subscriber;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_login(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('admin.login'));
    }

    public function test_the_dashboard_summarizes_the_month_without_cancelled_orders(): void
    {
        $order = Order::factory()->create(['total' => 50000, 'subtotal' => 50000]);
        $order->items()->create(['name' => 'Clásico', 'weight' => '500 g', 'unit_price' => 25000, 'quantity' => 2]);

        $cancelled = Order::factory()->create(['total' => 90000, 'status' => OrderStatus::Cancelled]);
        $cancelled->items()->create(['name' => 'Fantasma', 'weight' => '250 g', 'unit_price' => 90000, 'quantity' => 1]);

        Subscriber::factory()->count(3)->create();

        $this->actingAs(User::factory()->create())
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertViewHas('pendingOrders', 1)
            ->assertViewHas('monthOrders', 1)
            ->assertViewHas('monthSales', 50000)
            ->assertViewHas('subscribers', 3)
            ->assertSee('Café Clásico')
            ->assertDontSee('Fantasma');
    }

    public function test_orders_from_previous_months_do_not_count_as_sales_of_this_month(): void
    {
        $old = Order::factory()->create(['total' => 70000]);
        $old->forceFill(['created_at' => now()->subMonths(2)])->save();

        $this->actingAs(User::factory()->create())
            ->get(route('admin.dashboard'))
            ->assertViewHas('monthSales', 0)
            ->assertViewHas('pendingOrders', 1);
    }
}
