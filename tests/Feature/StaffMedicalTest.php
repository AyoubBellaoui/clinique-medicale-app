<?php

namespace Tests\Feature;

use App\Models\StaffMedical;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StaffMedicalTest extends TestCase
{
    use RefreshDatabase;

    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'prenom' => 'Sara',
            'nom' => 'Idrissi',
            'email' => 'sara.idrissi@clinique.test',
            'cin' => 'BB998877',
            'telephone' => '0600000000',
            'date_naissance' => '1985-04-12',
            'gender' => 'F',
            'adresse' => '12 Rue Test, Casablanca',
            'color' => 'teal',
            'role' => 'medecin',
            'specialite' => 'Cardiologie',
            'license_number' => 'LIC-90210',
            'degree' => 'Doctorat en Médecine',
            'school' => 'Faculté de Médecine',
            'grad_year' => 2010,
            'languages' => 'Français, Arabe',
            'contract_type' => 'CDI',
            'date_embauche' => '2020-01-01',
            'salaire' => 15000,
            'schedule' => 'Lun-Ven 9h-17h',
            'status' => 'actif',
        ], $overrides);
    }

    public function test_can_create_a_staff_member_with_all_required_fields(): void
    {
        $admin = User::factory()->role('admin')->create();

        $response = $this->actingAs($admin)->post(route('staff.store'), $this->validPayload());

        $response->assertRedirect(route('staff.index'));
        $this->assertDatabaseHas('staff_medicals', ['cin' => 'BB998877', 'email' => 'sara.idrissi@clinique.test']);
    }

    public function test_specialite_is_required_for_doctors(): void
    {
        $admin = User::factory()->role('admin')->create();

        $response = $this->actingAs($admin)->post(route('staff.store'), $this->validPayload(['specialite' => null]));

        $response->assertSessionHasErrors('specialite');
    }

    public function test_cannot_create_a_staff_member_with_a_duplicate_license_number(): void
    {
        $admin = User::factory()->role('admin')->create();
        StaffMedical::factory()->create(['license_number' => 'DUPLICATE-LIC']);

        $response = $this->actingAs($admin)->post(route('staff.store'), $this->validPayload([
            'license_number' => 'DUPLICATE-LIC',
            'cin' => 'CC112233',
            'email' => 'other@clinique.test',
        ]));

        $response->assertSessionHasErrors('license_number');
    }

    public function test_can_update_a_staff_member_without_tripping_its_own_unique_fields(): void
    {
        $admin = User::factory()->role('admin')->create();
        $staff = StaffMedical::factory()->create(['cin' => 'KEEP-CIN', 'nom' => 'Ancien']);

        $response = $this->actingAs($admin)->put(
            route('staff.update', $staff->id),
            $this->validPayload([
                'cin' => 'KEEP-CIN',
                'email' => $staff->email,
                'license_number' => $staff->license_number,
                'nom' => 'Nouveau',
            ])
        );

        $response->assertRedirect(route('staff.index'));
        $this->assertDatabaseHas('staff_medicals', ['id' => $staff->id, 'nom' => 'Nouveau']);
    }

    public function test_deleting_a_staff_member_archives_rather_than_destroys(): void
    {
        $admin = User::factory()->role('admin')->create();
        $staff = StaffMedical::factory()->create();

        $response = $this->actingAs($admin)->delete(route('staff.delete', $staff->id));

        $response->assertRedirect(route('staff.index'));
        $this->assertSoftDeleted('staff_medicals', ['id' => $staff->id]);
    }
}
