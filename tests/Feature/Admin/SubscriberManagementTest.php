<?php

namespace Tests\Feature\Admin;

use App\Models\Subscriber;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriberManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_see_the_subscribers(): void
    {
        $subscriber = Subscriber::factory()->create();

        $this->get(route('admin.subscribers.index'))->assertRedirect(route('admin.login'));
        $this->delete(route('admin.subscribers.destroy', $subscriber))->assertRedirect(route('admin.login'));
        $this->assertModelExists($subscriber);
    }

    public function test_admin_sees_the_subscribers(): void
    {
        Subscriber::factory()->create(['email' => 'cliente@correo.com']);

        $this->actingAs(User::factory()->create())
            ->get(route('admin.subscribers.index'))
            ->assertOk()
            ->assertSee('cliente@correo.com');
    }

    public function test_admin_can_remove_a_subscriber(): void
    {
        $subscriber = Subscriber::factory()->create();

        $this->actingAs(User::factory()->create())
            ->delete(route('admin.subscribers.destroy', $subscriber))
            ->assertRedirect(route('admin.subscribers.index'));

        $this->assertModelMissing($subscriber);
    }
}
