<?php

namespace Tests\Feature;

use App\Models\Consultation;
use App\Models\FileAttente;
use App\Models\Patient;
use App\Models\StaffMedical;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('dashboard.show'));

        $response->assertRedirect(route('login.show'));
    }

    public function test_dashboard_reflects_real_data_not_placeholders(): void
    {
        $user = User::factory()->create();
        $doctor = StaffMedical::factory()->create(['specialite' => 'Cardiologie']);
        $patient = Patient::factory()->create();
        Consultation::factory()->create(['patient_id' => $patient->id, 'staff_id' => $doctor->id]);
        FileAttente::factory()->create([
            'patient_id' => $patient->id,
            'staff_id' => $doctor->id,
            'statut' => 'en_attente',
            'arrived_at' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('dashboard.show'));

        $response->assertStatus(200);
        $response->assertViewHas('patientsInQueue', 1);
        $response->assertViewHas('specialtyLabels', ['Cardiologie']);
        $response->assertViewHas('specialtyData', [100]);

        $chartConsultations = $response->viewData('chartConsultations');
        $this->assertSame(1, array_sum($chartConsultations));
    }
}
