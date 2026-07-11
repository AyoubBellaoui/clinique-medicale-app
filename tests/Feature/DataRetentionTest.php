<?php

namespace Tests\Feature;

use App\Models\Consultation;
use App\Models\Facture;
use App\Models\FileAttente;
use App\Models\Patient;
use App\Models\RendezVous;
use App\Models\StaffMedical;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * consultations/rendez_vous/file_attentes/ordonnances/factures all
 * cascadeOnDelete() from patients and staff_medicals. Before Patient and
 * StaffMedical gained SoftDeletes, a single "delete" click on either
 * permanently destroyed every dependent medical and billing record. These
 * tests guard against that regressing.
 */
class DataRetentionTest extends TestCase
{
    use RefreshDatabase;

    public function test_deleting_a_patient_preserves_their_consultations_appointments_and_invoices(): void
    {
        $user = User::factory()->create();
        $patient = Patient::factory()->create();
        $doctor = StaffMedical::factory()->create();

        $consultation = Consultation::factory()->create(['patient_id' => $patient->id, 'staff_id' => $doctor->id]);
        $rdv = RendezVous::factory()->create(['patient_id' => $patient->id, 'staff_id' => $doctor->id]);
        $facture = Facture::factory()->create(['patient_id' => $patient->id]);

        $this->actingAs($user)->delete(route('patients.delete', $patient->id));

        $this->assertDatabaseHas('consultations', ['id' => $consultation->id]);
        $this->assertDatabaseHas('rendez_vous', ['id' => $rdv->id]);
        $this->assertDatabaseHas('factures', ['id' => $facture->id]);
        $this->assertSoftDeleted('patients', ['id' => $patient->id]);

        // The patient no longer shows up in normal listings...
        $this->assertNull(Patient::find($patient->id));
        // ...but historical records can still resolve who they belong to.
        $this->assertSame($patient->id, $consultation->fresh()->patient->id);
    }

    public function test_deleting_a_staff_member_preserves_their_consultations_and_appointments(): void
    {
        $user = User::factory()->role('admin')->create();
        $doctor = StaffMedical::factory()->create();
        $patient = Patient::factory()->create();

        $consultation = Consultation::factory()->create(['patient_id' => $patient->id, 'staff_id' => $doctor->id]);
        $rdv = RendezVous::factory()->create(['patient_id' => $patient->id, 'staff_id' => $doctor->id]);
        $queueEntry = FileAttente::factory()->create(['patient_id' => $patient->id, 'staff_id' => $doctor->id]);

        $this->actingAs($user)->delete(route('staff.delete', $doctor->id));

        $this->assertDatabaseHas('consultations', ['id' => $consultation->id]);
        $this->assertDatabaseHas('rendez_vous', ['id' => $rdv->id]);
        $this->assertDatabaseHas('file_attentes', ['id' => $queueEntry->id]);
        $this->assertSoftDeleted('staff_medicals', ['id' => $doctor->id]);

        $this->assertNull(StaffMedical::find($doctor->id));
        $this->assertSame($doctor->id, $consultation->fresh()->staff->id);
    }

    public function test_archived_patients_are_excluded_from_the_active_index(): void
    {
        $user = User::factory()->create();
        $keep = Patient::factory()->create();
        $archived = Patient::factory()->create();
        $archived->delete();

        $ids = Patient::pluck('id');

        $this->assertTrue($ids->contains($keep->id));
        $this->assertFalse($ids->contains($archived->id));
    }
}
