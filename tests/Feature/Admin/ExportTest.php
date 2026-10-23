<?php

namespace Tests\Feature\Admin;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Subscriber;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_export(): void
    {
        $this->get(route('admin.orders.export'))->assertRedirect(route('admin.login'));
        $this->get(route('admin.subscribers.export'))->assertRedirect(route('admin.login'));
    }

    public function test_admin_exports_the_orders_filtered_by_status(): void
    {
        Order::factory()->create(['customer_name' => 'Ana Pendiente']);
        Order::factory()->create(['customer_name' => 'Luis Enviado', 'status' => OrderStatus::Shipped]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('admin.orders.export', ['estado' => 'shipped']))
            ->assertOk()
            ->assertDownload();

        $csv = $response->streamedContent();

        $this->assertStringContainsString('Luis Enviado', $csv);
        $this->assertStringNotContainsString('Ana Pendiente', $csv);
    }

    public function test_text_typed_by_customers_cannot_run_as_a_spreadsheet_formula(): void
    {
        Order::factory()->create(['customer_name' => '=HYPERLINK("http://malo.test")']);

        $csv = $this->actingAs(User::factory()->create())
            ->get(route('admin.orders.export'))
            ->streamedContent();

        $this->assertStringContainsString("'=HYPERLINK", $csv);
    }

    public function test_admin_exports_the_subscribers(): void
    {
        Subscriber::factory()->create(['email' => 'cliente@correo.com']);

        $csv = $this->actingAs(User::factory()->create())
            ->get(route('admin.subscribers.export'))
            ->assertOk()
            ->streamedContent();

        $this->assertStringContainsString('Correo', $csv);
        $this->assertStringContainsString('cliente@correo.com', $csv);
    }
}
