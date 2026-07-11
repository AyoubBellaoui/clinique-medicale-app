<?php

namespace Database\Factories;

use App\Models\Ordonnance;
use App\Models\Patient;
use App\Models\StaffMedical;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ordonnance>
 */
class OrdonnanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'patient_id' => Patient::factory(),
            'staff_id' => StaffMedical::factory(),
            'date_prescription' => now()->format('Y-m-d'),
            'renouvelable' => 0,
            'substitution_autorisee' => true,
            'contenu' => fake()->sentence(6),
        ];
    }
}
