<?php

namespace Tests\Feature;

use App\Models\Consultation;
use App\Models\FileAttente;
use App\Models\Patient;
use App\Models\RendezVous;
use App\Models\StaffMedical;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConsultationTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_medical_role_cannot_access_consultations(): void
    {
        $user = User::factory()->role('secretariat')->create();

        $response = $this->actingAs($user)->get(route('consultations.index'));

        $response->assertRedirect(route('dashboard.show'));
    }

    public function test_medical_role_can_view_consultations(): void
    {
        $user = User::factory()->role('medecin')->create();

        $response = $this->actingAs($user)->get(route('consultations.index'));

        $response->assertStatus(200);
    }

    public function test_creating_a_consultation_for_a_waiting_queue_entry_marks_it_in_progress(): void
    {
        $user = User::factory()->role('medecin')->create();
        $patient = Patient::factory()->create();
        $doctor = StaffMedical::factory()->create();
        $queueEntry = FileAttente::factory()->create([
            'patient_id' => $patient->id,
            'staff_id' => $doctor->id,
            'statut' => 'en_attente',
        ]);

        $response = $this->actingAs($user)->post(route('consultations.store'), [
            'patient_id' => $patient->id,
            'staff_id' => $doctor->id,
            'file_attente_id' => $queueEntry->id,
            'date' => now()->format('Y-m-d'),
            'heure' => '09:30',
            'type_consultation' => 'standard',
        ]);

        $response->assertRedirect(route('consultations.index'));
        $this->assertSame('en_cours', $queueEntry->fresh()->statut);
        $this->assertDatabaseHas('consultations', [
            'patient_id' => $patient->id,
            'file_attente_id' => $queueEntry->id,
        ]);
    }

    public function test_consultation_auto_links_to_todays_in_progress_queue_entry_when_not_specified(): void
    {
        $user = User::factory()->role('medecin')->create();
        $patient = Patient::factory()->create();
        $doctor = StaffMedical::factory()->create();
        $queueEntry = FileAttente::factory()->create([
            'patient_id' => $patient->id,
            'staff_id' => $doctor->id,
            'statut' => 'en_cours',
            'arrived_at' => now(),
        ]);

        $this->actingAs($user)->post(route('consultations.store'), [
            'patient_id' => $patient->id,
            'staff_id' => $doctor->id,
            'date' => now()->format('Y-m-d'),
            'heure' => '09:30',
            'type_consultation' => 'standard',
        ]);

        $consultation = Consultation::where('patient_id', $patient->id)->firstOrFail();
        $this->assertSame($queueEntry->id, $consultation->file_attente_id);
    }

    public function test_a_follow_up_date_schedules_a_new_appointment(): void
    {
        $user = User::factory()->role('medecin')->create();
        $patient = Patient::factory()->create();
        $doctor = StaffMedical::factory()->create();
        $followUpDate = now()->addWeek()->format('Y-m-d');

        $this->actingAs($user)->post(route('consultations.store'), [
            'patient_id' => $patient->id,
            'staff_id' => $doctor->id,
            'date' => now()->format('Y-m-d'),
            'heure' => '09:30',
            'type_consultation' => 'standard',
            'prochain_rdv_date' => $followUpDate,
        ]);

        $this->assertSame(
            1,
            RendezVous::where('patient_id', $patient->id)
                ->where('staff_id', $doctor->id)
                ->where('type_consultation', 'suivi')
                ->whereDate('date', $followUpDate)
                ->count()
        );
    }

    public function test_a_follow_up_is_skipped_when_the_doctor_already_has_a_slot_booked(): void
    {
        $user = User::factory()->role('medecin')->create();
        $patient = Patient::factory()->create();
        $doctor = StaffMedical::factory()->create();
        $followUpDate = now()->addWeek()->format('Y-m-d');

        RendezVous::factory()->create([
            'staff_id' => $doctor->id,
            'date' => $followUpDate,
            'heure' => '09:00',
            'statut' => 'programme',
        ]);

        $this->actingAs($user)->post(route('consultations.store'), [
            'patient_id' => $patient->id,
            'staff_id' => $doctor->id,
            'date' => now()->format('Y-m-d'),
            'heure' => '09:30',
            'type_consultation' => 'standard',
            'prochain_rdv_date' => $followUpDate,
        ]);

        $this->assertSame(
            1,
            RendezVous::where('staff_id', $doctor->id)->whereDate('date', $followUpDate)->count()
        );
    }
}
