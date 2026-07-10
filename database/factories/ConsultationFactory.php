<?php

namespace Database\Factories;

use App\Models\Consultation;
use App\Models\Patient;
use App\Models\StaffMedical;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Consultation>
 */
class ConsultationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date_consultation' => now(),
            'type_consultation' => 'standard',
            'motif' => fake()->sentence(4),
            'diagnostic' => fake()->sentence(6),
            'notes' => fake()->sentence(8),
            'patient_id' => Patient::factory(),
            'staff_id' => StaffMedical::factory(),
        ];
    }
}
