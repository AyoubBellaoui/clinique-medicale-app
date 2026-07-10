<?php

namespace Database\Factories;

use App\Models\StaffMedical;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StaffMedical>
 */
class StaffMedicalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nom' => fake()->lastName(),
            'prenom' => fake()->firstName(),
            'cin' => strtoupper(fake()->unique()->bothify('??######')),
            'gender' => fake()->randomElement(['M', 'F']),
            'date_naissance' => fake()->dateTimeBetween('-60 years', '-25 years')->format('Y-m-d'),
            'email' => fake()->unique()->safeEmail(),
            'telephone' => fake()->numerify('06########'),
            'specialite' => fake()->randomElement(['Cardiologie', 'Médecine Générale', 'Pédiatrie', 'Dermatologie', 'Ophtalmologie']),
            'license_number' => strtoupper(fake()->unique()->bothify('LIC-#####')),
            'degree' => 'Doctorat en Médecine',
            'school' => 'Faculté de Médecine',
            'grad_year' => fake()->numberBetween(1990, 2020),
            'languages' => 'Français, Arabe',
            'date_embauche' => fake()->dateTimeBetween('-10 years', '-1 month')->format('Y-m-d'),
            'salaire' => fake()->numberBetween(8000, 25000),
            'contract_type' => 'CDI',
            'schedule' => 'Lun-Ven 9h-17h',
            'status' => 'actif',
            'role' => 'medecin',
        ];
    }
}
