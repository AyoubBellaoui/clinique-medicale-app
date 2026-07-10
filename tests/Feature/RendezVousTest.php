<?php

namespace Tests\Feature;

use App\Models\Patient;
use App\Models\RendezVous;
use App\Models\StaffMedical;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RendezVousTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_an_appointment(): void
    {
        $user = User::factory()->create();
        $patient = Patient::factory()->create();
        $doctor = StaffMedical::factory()->create();

        $response = $this->actingAs($user)->post(route('appointments.store'), [
            'patient_id' => $patient->id,
            'staff_id' => $doctor->id,
            'date' => now()->addDay()->format('Y-m-d'),
            'heure' => '10:00',
            'type_consultation' => 'standard',
            'duree' => 30,
            'priorite' => 'normale',
        ]);

        $response->assertRedirect(route('appointments.index'));
        $this->assertDatabaseHas('rendez_vous', [
            'patient_id' => $patient->id,
            'staff_id' => $doctor->id,
            'heure' => '10:00',
        ]);
    }

    public function test_cannot_double_book_the_same_doctor_slot(): void
    {
        $user = User::factory()->create();
        $doctor = StaffMedical::factory()->create();
        $date = now()->addDay()->format('Y-m-d');

        RendezVous::factory()->create([
            'staff_id' => $doctor->id,
            'date' => $date,
            'heure' => '10:00',
            'statut' => 'programme',
        ]);

        $response = $this->actingAs($user)->post(route('appointments.store'), [
            'patient_id' => Patient::factory()->create()->id,
            'staff_id' => $doctor->id,
            'date' => $date,
            'heure' => '10:00',
            'type_consultation' => 'standard',
            'duree' => 30,
            'priorite' => 'normale',
        ]);

        $response->assertSessionHasErrors('heure');
        $this->assertSame(1, RendezVous::where('staff_id', $doctor->id)->whereDate('date', $date)->count());
    }

    public function test_cannot_book_a_past_time_slot_today(): void
    {
        $this->travelTo(now()->setTime(12, 0));

        $user = User::factory()->create();
        $doctor = StaffMedical::factory()->create();

        $response = $this->actingAs($user)->post(route('appointments.store'), [
            'patient_id' => Patient::factory()->create()->id,
            'staff_id' => $doctor->id,
            'date' => now()->format('Y-m-d'),
            'heure' => '09:00',
            'type_consultation' => 'standard',
            'duree' => 30,
            'priorite' => 'normale',
        ]);

        $response->assertSessionHasErrors('heure');
    }
}
