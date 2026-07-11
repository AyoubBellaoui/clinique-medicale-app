<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_a_user_account(): void
    {
        $admin = User::factory()->role('admin')->create();

        $response = $this->actingAs($admin)->post(route('users.store'), [
            'email' => 'new.user@clinique.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'secretariat',
        ]);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', ['email' => 'new.user@clinique.test', 'role' => 'secretariat']);
    }

    public function test_password_confirmation_must_match(): void
    {
        $admin = User::factory()->role('admin')->create();

        $response = $this->actingAs($admin)->post(route('users.store'), [
            'email' => 'new.user@clinique.test',
            'password' => 'password123',
            'password_confirmation' => 'does-not-match',
            'role' => 'secretariat',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertDatabaseMissing('users', ['email' => 'new.user@clinique.test']);
    }

    public function test_cannot_demote_the_last_admin(): void
    {
        $admin = User::factory()->role('admin')->create();

        $response = $this->actingAs($admin)->put(route('users.update', $admin->id), [
            'email' => $admin->email,
            'role' => 'secretariat',
        ]);

        $response->assertSessionHasErrors('role');
        $this->assertSame('admin', $admin->fresh()->role);
    }

    public function test_can_demote_an_admin_when_another_admin_remains(): void
    {
        $admin = User::factory()->role('admin')->create();
        $secondAdmin = User::factory()->role('admin')->create();

        $response = $this->actingAs($admin)->put(route('users.update', $secondAdmin->id), [
            'email' => $secondAdmin->email,
            'role' => 'secretariat',
        ]);

        $response->assertRedirect(route('users.index'));
        $this->assertSame('secretariat', $secondAdmin->fresh()->role);
    }

    public function test_cannot_delete_the_last_admin(): void
    {
        $admin = User::factory()->role('admin')->create();

        $response = $this->actingAs($admin)->delete(route('users.delete', $admin->id));

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_cannot_delete_your_own_account(): void
    {
        $admin = User::factory()->role('admin')->create();
        $secondAdmin = User::factory()->role('admin')->create();

        $response = $this->actingAs($secondAdmin)->delete(route('users.delete', $secondAdmin->id));

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', ['id' => $secondAdmin->id]);
    }

    public function test_admin_can_reset_another_users_password(): void
    {
        $admin = User::factory()->role('admin')->create();
        $target = User::factory()->create();

        $response = $this->actingAs($admin)->put(route('users.resetPassword', $target->id), [
            'password' => 'newpassword1',
            'password_confirmation' => 'newpassword1',
        ]);

        $response->assertRedirect(route('users.index'));
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('newpassword1', $target->fresh()->password));
    }

    public function test_non_admin_cannot_manage_users(): void
    {
        $user = User::factory()->role('secretariat')->create();

        $response = $this->actingAs($user)->get(route('users.index'));

        $response->assertRedirect(route('dashboard.show'));
    }
}
