<?php

namespace Tests\Feature;

use App\Models\Ordonnance;
use App\Models\Patient;
use App\Models\StaffMedical;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrdonnanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_medical_role_cannot_access_prescriptions(): void
    {
        $user = User::factory()->role('secretariat')->create();

        $response = $this->actingAs($user)->get(route('prescriptions.index'));

        $response->assertRedirect(route('dashboard.show'));
    }

    public function test_can_create_a_prescription_with_multiple_medications(): void
    {
        $user = User::factory()->role('medecin')->create();
        $patient = Patient::factory()->create();
        $doctor = StaffMedical::factory()->create();

        $response = $this->actingAs($user)->post(route('prescriptions.store'), [
            'patient_id' => $patient->id,
            'staff_id' => $doctor->id,
            'date' => now()->format('Y-m-d'),
            'renouvelable' => 1,
            'meds' => [
                ['name' => 'Paracétamol', 'dosage' => '500mg', 'duration' => '5 jours'],
                ['name' => 'Ibuprofène', 'dosage' => '200mg', 'duration' => '3 jours'],
            ],
        ]);

        $response->assertRedirect(route('prescriptions.index'));

        $ordonnance = Ordonnance::where('patient_id', $patient->id)->firstOrFail();
        $this->assertCount(2, $ordonnance->medicaments);
        $this->assertStringContainsString('Paracétamol', $ordonnance->contenu);
        $this->assertStringContainsString('Ibuprofène', $ordonnance->contenu);
    }

    public function test_a_prescription_requires_at_least_one_medication(): void
    {
        $user = User::factory()->role('medecin')->create();
        $patient = Patient::factory()->create();
        $doctor = StaffMedical::factory()->create();

        $response = $this->actingAs($user)->post(route('prescriptions.store'), [
            'patient_id' => $patient->id,
            'staff_id' => $doctor->id,
            'date' => now()->format('Y-m-d'),
            'renouvelable' => 0,
            'meds' => [],
        ]);

        $response->assertSessionHasErrors('meds');
        $this->assertSame(0, Ordonnance::count());
    }

    public function test_updating_a_prescription_replaces_its_medication_lines(): void
    {
        $user = User::factory()->role('medecin')->create();
        $ordonnance = Ordonnance::factory()->create();
        $ordonnance->medicaments()->create(['nom' => 'Ancien médicament']);

        $response = $this->actingAs($user)->put(route('prescriptions.update', $ordonnance->id), [
            'patient_id' => $ordonnance->patient_id,
            'staff_id' => $ordonnance->staff_id,
            'date' => now()->format('Y-m-d'),
            'renouvelable' => 0,
            'meds' => [
                ['name' => 'Nouveau médicament'],
            ],
        ]);

        $response->assertRedirect(route('prescriptions.index'));
        $ordonnance->refresh();
        $this->assertCount(1, $ordonnance->medicaments);
        $this->assertSame('Nouveau médicament', $ordonnance->medicaments->first()->nom);
    }

    public function test_can_delete_a_prescription(): void
    {
        $user = User::factory()->role('medecin')->create();
        $ordonnance = Ordonnance::factory()->create();

        $response = $this->actingAs($user)->delete(route('prescriptions.delete', $ordonnance->id));

        $response->assertRedirect(route('prescriptions.index'));
        $this->assertDatabaseMissing('ordonnances', ['id' => $ordonnance->id]);
    }
}
