<?php

namespace Tests\Feature;

use App\Models\Consultation;
use App\Models\Facture;
use App\Models\FileAttente;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FacturesTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_support_role_cannot_access_billing(): void
    {
        $user = User::factory()->role('medecin')->create();

        $response = $this->actingAs($user)->get(route('billing.index'));

        $response->assertRedirect(route('dashboard.show'));
    }

    public function test_invoice_totals_are_computed_from_services_discount_and_tax(): void
    {
        $user = User::factory()->role('secretariat')->create();
        $patient = Patient::factory()->create();

        // 2 x 200 + 1 x 150 = 550 sous-total; 10% discount -> 495; 20% tax -> 594
        $response = $this->actingAs($user)->post(route('billing.store'), [
            'patient_id' => $patient->id,
            'date' => now()->format('Y-m-d'),
            'payment_method' => 'especes',
            'discount' => 10,
            'tax' => 20,
            'services' => [
                ['name' => 'Consultation', 'qty' => 2, 'price' => 200],
                ['name' => 'Analyse', 'qty' => 1, 'price' => 150],
            ],
        ]);

        $response->assertRedirect(route('billing.index'));

        $facture = Facture::where('patient_id', $patient->id)->firstOrFail();
        $this->assertEquals(550.0, (float) $facture->sous_total);
        $this->assertEquals(594.0, (float) $facture->total_ttc);
        $this->assertSame('en_attente', $facture->statut);
        $this->assertCount(2, $facture->lignes);
    }

    public function test_invoice_requires_at_least_one_service_line(): void
    {
        $user = User::factory()->role('secretariat')->create();
        $patient = Patient::factory()->create();

        $response = $this->actingAs($user)->post(route('billing.store'), [
            'patient_id' => $patient->id,
            'date' => now()->format('Y-m-d'),
            'payment_method' => 'especes',
            'services' => [],
        ]);

        $response->assertSessionHasErrors('services');
        $this->assertSame(0, Facture::count());
    }

    public function test_marking_an_invoice_paid_sets_statut_and_paid_at(): void
    {
        $user = User::factory()->role('secretariat')->create();
        $facture = Facture::factory()->create(['statut' => 'en_attente', 'paid_at' => null]);

        $response = $this->actingAs($user)->put(route('billing.markPaid', $facture->id));

        $response->assertRedirect(route('billing.index'));
        $facture->refresh();
        $this->assertSame('paye', $facture->statut);
        $this->assertNotNull($facture->paid_at);
    }

    public function test_marking_an_invoice_paid_closes_out_its_linked_queue_entry(): void
    {
        $user = User::factory()->role('secretariat')->create();
        $queueEntry = FileAttente::factory()->create(['statut' => 'en_cours']);
        $consultation = Consultation::factory()->create([
            'patient_id' => $queueEntry->patient_id,
            'staff_id' => $queueEntry->staff_id,
            'file_attente_id' => $queueEntry->id,
        ]);
        $facture = Facture::factory()->create([
            'patient_id' => $queueEntry->patient_id,
            'consultation_id' => $consultation->id,
            'statut' => 'en_attente',
        ]);

        $this->actingAs($user)->put(route('billing.markPaid', $facture->id));

        $this->assertSame('termine', $queueEntry->fresh()->statut);
    }

    public function test_an_overdue_unpaid_invoice_is_flagged_as_overdue(): void
    {
        $facture = Facture::factory()->create([
            'statut' => 'en_attente',
            'date_echeance' => now()->subDay(),
        ]);

        $this->assertTrue($facture->isOverdue());
    }

    public function test_a_paid_invoice_is_never_overdue_even_past_its_due_date(): void
    {
        $facture = Facture::factory()->create([
            'statut' => 'paye',
            'date_echeance' => now()->subDay(),
        ]);

        $this->assertFalse($facture->isOverdue());
    }
}
