<?php

namespace Database\Factories;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Patient>
 */
class PatientFactory extends Factory
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
            'date_naissance' => fake()->dateTimeBetween('-90 years', '-1 year')->format('Y-m-d'),
            'genre' => fake()->randomElement(['M', 'F']),
            'statut_marital' => fake()->randomElement(['celibataire', 'marie', 'divorce', 'veuf']),
            'cin' => strtoupper(fake()->unique()->bothify('??######')),
            'telephone' => fake()->numerify('06########'),
            'adresse' => fake()->address(),
            'statut_dossier' => 'actif',
        ];
    }
}
