<?php

namespace Tests\Feature;

use App\Models\Patient;
use App\Models\StaffMedical;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatientTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_view_patients(): void
    {
        $response = $this->get(route('patients.index'));

        $response->assertRedirect(route('login.show'));
    }

    public function test_authenticated_user_can_view_patients_index(): void
    {
        $user = User::factory()->create();
        Patient::factory()->count(3)->create();

        $response = $this->actingAs($user)->get(route('patients.index'));

        $response->assertStatus(200);
    }

    public function test_can_create_a_patient(): void
    {
        $user = User::factory()->create();
        $doctor = StaffMedical::factory()->create();

        $response = $this->actingAs($user)->post(route('patients.store'), [
            'prenom' => 'Zahra',
            'nom' => 'Rachidi',
            'cin' => 'AB123456',
            'genre' => 'F',
            'medecin_id' => $doctor->id,
        ]);

        $response->assertRedirect(route('patients.index'));
        $this->assertDatabaseHas('patients', [
            'cin' => 'AB123456',
            'nom' => 'Rachidi',
            'prenom' => 'Zahra',
        ]);
    }

    public function test_cannot_create_a_patient_with_a_duplicate_cin(): void
    {
        $user = User::factory()->create();
        Patient::factory()->create(['cin' => 'DUPLICATE1']);

        $response = $this->actingAs($user)->post(route('patients.store'), [
            'prenom' => 'Kamal',
            'nom' => 'Najm',
            'cin' => 'DUPLICATE1',
            'genre' => 'M',
        ]);

        $response->assertSessionHasErrors('cin');
        $this->assertSame(1, Patient::where('cin', 'DUPLICATE1')->count());
    }

    public function test_can_update_a_patient_without_tripping_its_own_unique_cin(): void
    {
        // Regression test: the controller ignores the patient's own row when
        // checking CIN/email uniqueness on update — resaving the same CIN must not fail.
        $user = User::factory()->create();
        $patient = Patient::factory()->create(['cin' => 'KEEPME1', 'nom' => 'Ancien']);

        $response = $this->actingAs($user)->put(route('patients.update', $patient->id), [
            'prenom' => $patient->prenom,
            'nom' => 'Nouveau',
            'cin' => 'KEEPME1',
            'genre' => $patient->genre,
        ]);

        $response->assertRedirect(route('patients.index'));
        $this->assertDatabaseHas('patients', [
            'id' => $patient->id,
            'nom' => 'Nouveau',
            'cin' => 'KEEPME1',
        ]);
    }

    public function test_can_delete_a_patient(): void
    {
        // "Delete" archives (soft-deletes) rather than destroying the row outright —
        // see DataRetentionTest for why: dependent consultations/invoices/etc. must survive.
        $user = User::factory()->create();
        $patient = Patient::factory()->create();

        $response = $this->actingAs($user)->delete(route('patients.delete', $patient->id));

        $response->assertRedirect(route('patients.index'));
        $this->assertSoftDeleted('patients', ['id' => $patient->id]);
        $this->assertNull(Patient::find($patient->id));
    }
}
