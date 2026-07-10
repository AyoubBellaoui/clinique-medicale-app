<?php

namespace Database\Factories;

use App\Models\Patient;
use App\Models\RendezVous;
use App\Models\StaffMedical;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RendezVous>
 */
class RendezVousFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date' => now()->addDays(fake()->numberBetween(1, 20))->format('Y-m-d'),
            'heure' => fake()->randomElement(['09:00', '10:00', '11:00', '14:00', '15:00']),
            'statut' => 'programme',
            'motif' => fake()->sentence(4),
            'type_consultation' => 'standard',
            'duree' => 30,
            'priorite' => 'normale',
            'patient_id' => Patient::factory(),
            'staff_id' => StaffMedical::factory(),
        ];
    }
}
