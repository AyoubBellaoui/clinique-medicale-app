<?php

namespace Tests\Feature;

use App\Models\Patient;
use App\Models\StaffMedical;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_search(): void
    {
        $response = $this->get(route('search', ['q' => 'test']));

        $response->assertRedirect(route('login.show'));
    }

    public function test_short_queries_return_no_results(): void
    {
        $user = User::factory()->create();
        Patient::factory()->create(['nom' => 'Zahra']);

        $response = $this->actingAs($user)->getJson(route('search', ['q' => 'z']));

        $response->assertOk()->assertJson([]);
    }

    public function test_finds_a_patient_by_name(): void
    {
        $user = User::factory()->create();
        $patient = Patient::factory()->create(['nom' => 'Rachidi', 'prenom' => 'Zahra']);

        $response = $this->actingAs($user)->getJson(route('search', ['q' => 'Rachidi']));

        $response->assertOk();
        $response->assertJsonFragment(['title' => $patient->full_name, 'type' => 'patient']);
    }

    public function test_archived_patients_do_not_appear_in_results(): void
    {
        $user = User::factory()->create();
        $patient = Patient::factory()->create(['nom' => 'Archivé']);
        $patient->delete();

        $response = $this->actingAs($user)->getJson(route('search', ['q' => 'Archivé']));

        $response->assertOk()->assertJson([]);
    }

    public function test_non_admin_does_not_get_staff_management_links(): void
    {
        $user = User::factory()->role('secretariat')->create();
        StaffMedical::factory()->create(['nom' => 'Bennani']);

        $response = $this->actingAs($user)->getJson(route('search', ['q' => 'Bennani']));

        $response->assertOk()->assertJson([]);
    }

    public function test_admin_finds_staff_by_name(): void
    {
        $admin = User::factory()->role('admin')->create();
        $staff = StaffMedical::factory()->create(['nom' => 'Bennani']);

        $response = $this->actingAs($admin)->getJson(route('search', ['q' => 'Bennani']));

        $response->assertOk();
        $response->assertJsonFragment(['title' => $staff->full_name, 'type' => 'staff']);
    }
}
