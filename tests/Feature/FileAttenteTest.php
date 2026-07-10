<?php

namespace Tests\Feature;

use App\Models\FileAttente;
use App\Models\Patient;
use App\Models\RendezVous;
use App\Models\StaffMedical;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FileAttenteTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_add_a_walk_in_patient_to_the_queue(): void
    {
        $user = User::factory()->create();
        $patient = Patient::factory()->create();
        $doctor = StaffMedical::factory()->create();

        $response = $this->actingAs($user)->post(route('fileAttente.store'), [
            'patient_id' => $patient->id,
            'staff_id' => $doctor->id,
            'priorite' => 'normale',
            'type_visite' => 'sans_rdv',
        ]);

        $response->assertRedirect(route('fileAttente.index'));
        $this->assertDatabaseHas('file_attentes', [
            'patient_id' => $patient->id,
            'staff_id' => $doctor->id,
            'statut' => 'en_attente',
        ]);
    }

    public function test_checking_in_for_a_scheduled_appointment_confirms_it(): void
    {
        $user = User::factory()->create();
        $rdv = RendezVous::factory()->create(['statut' => 'programme']);

        $this->actingAs($user)->post(route('fileAttente.store'), [
            'patient_id' => $rdv->patient_id,
            'staff_id' => $rdv->staff_id,
            'rendez_vous_id' => $rdv->id,
            'priorite' => 'normale',
            'type_visite' => 'avec_rdv',
        ]);

        $this->assertSame('confirme', $rdv->fresh()->statut);
    }

    public function test_a_rendez_vous_cannot_be_checked_in_twice(): void
    {
        $user = User::factory()->create();
        $rdv = RendezVous::factory()->create();
        FileAttente::factory()->create(['rendez_vous_id' => $rdv->id]);

        $response = $this->actingAs($user)->post(route('fileAttente.store'), [
            'patient_id' => $rdv->patient_id,
            'staff_id' => $rdv->staff_id,
            'rendez_vous_id' => $rdv->id,
            'priorite' => 'normale',
            'type_visite' => 'avec_rdv',
        ]);

        $response->assertSessionHasErrors('rendez_vous_id');
        $this->assertSame(1, FileAttente::where('rendez_vous_id', $rdv->id)->count());
    }

    public function test_can_update_queue_entry_status(): void
    {
        $user = User::factory()->create();
        $entry = FileAttente::factory()->create(['statut' => 'en_attente']);

        $response = $this->actingAs($user)->put(route('fileAttente.update', $entry->id), [
            'patient_id' => $entry->patient_id,
            'staff_id' => $entry->staff_id,
            'statut' => 'en_cours',
            'priorite' => $entry->priorite,
            'type_visite' => $entry->type_visite,
        ]);

        $response->assertRedirect(route('fileAttente.index'));
        $this->assertSame('en_cours', $entry->fresh()->statut);
    }
}
