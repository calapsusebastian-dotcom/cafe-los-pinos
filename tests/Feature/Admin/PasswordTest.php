<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_change_a_password(): void
    {
        $this->get(route('admin.password.edit'))->assertRedirect(route('admin.login'));
        $this->put(route('admin.password.update'), [])->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_change_the_password(): void
    {
        $user = User::factory()->create(['password' => 'clave-actual']);

        $this->actingAs($user)
            ->put(route('admin.password.update'), [
                'current_password' => 'clave-actual',
                'password' => 'clave-nueva-123',
                'password_confirmation' => 'clave-nueva-123',
            ])
            ->assertRedirect(route('admin.password.edit'))
            ->assertSessionHas('status');

        $this->assertTrue(Hash::check('clave-nueva-123', $user->fresh()->password));
    }

    public function test_the_current_password_must_be_correct(): void
    {
        $user = User::factory()->create(['password' => 'clave-actual']);

        $this->actingAs($user)
            ->put(route('admin.password.update'), [
                'current_password' => 'otra',
                'password' => 'clave-nueva-123',
                'password_confirmation' => 'clave-nueva-123',
            ])
            ->assertSessionHasErrors('current_password');

        $this->assertTrue(Hash::check('clave-actual', $user->fresh()->password));
    }

    public function test_the_new_password_needs_confirmation_and_length(): void
    {
        $user = User::factory()->create(['password' => 'clave-actual']);

        $this->actingAs($user)
            ->put(route('admin.password.update'), [
                'current_password' => 'clave-actual',
                'password' => 'corta',
                'password_confirmation' => 'distinta',
            ])
            ->assertSessionHasErrors('password');
    }
}
