<?php

namespace Database\Factories;

use App\Models\FileAttente;
use App\Models\Patient;
use App\Models\StaffMedical;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FileAttente>
 */
class FileAttenteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'arrived_at' => now(),
            'position' => 1,
            'statut' => 'en_attente',
            'priorite' => 'normale',
            'type_visite' => 'sans_rdv',
            'patient_id' => Patient::factory(),
            'staff_id' => StaffMedical::factory(),
        ];
    }
}
