<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccessControlTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_cannot_view_staff_management(): void
    {
        $user = User::factory()->role('medecin')->create();

        $response = $this->actingAs($user)->get(route('staff.index'));

        $response->assertRedirect(route('dashboard.show'));
    }

    public function test_admin_can_view_staff_management(): void
    {
        $admin = User::factory()->role('admin')->create();

        $response = $this->actingAs($admin)->get(route('staff.index'));

        $response->assertStatus(200);
    }

    public function test_non_admin_cannot_view_user_management(): void
    {
        $user = User::factory()->role('secretariat')->create();

        $response = $this->actingAs($user)->get(route('users.index'));

        $response->assertRedirect(route('dashboard.show'));
    }

    public function test_medical_staff_cannot_access_billing(): void
    {
        $user = User::factory()->role('infirmier')->create();

        $response = $this->actingAs($user)->get(route('billing.index'));

        $response->assertRedirect(route('dashboard.show'));
    }

    public function test_support_staff_cannot_access_consultations(): void
    {
        $user = User::factory()->role('technicien')->create();

        $response = $this->actingAs($user)->get(route('consultations.index'));

        $response->assertRedirect(route('dashboard.show'));
    }
}
