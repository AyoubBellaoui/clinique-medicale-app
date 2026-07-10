<?php

namespace Database\Factories;

use App\Models\Facture;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Facture>
 */
class FactureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'numero' => 'FAC-' . now()->year . '-' . str_pad((string) fake()->unique()->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT),
            'patient_id' => Patient::factory(),
            'date_facturation' => now()->format('Y-m-d'),
            'mode_paiement' => 'especes',
            'statut' => 'en_attente',
            'remise' => 0,
            'tva' => 0,
            'sous_total' => 100,
            'total_ttc' => 100,
        ];
    }
}
