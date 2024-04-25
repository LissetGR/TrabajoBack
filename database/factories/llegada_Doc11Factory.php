<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\llegada_Doc11>
 */
class llegada_Doc11Factory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fecha' => $this->faker->date(),
            'doc1' => $this->faker->randomElement(['Cert. di nascita', 'Procura']),
            'doc2' => $this->faker->randomElement(['Stato libero', 'Sentenza di divorzio', 'Atto di morte']),
        ];
    }
}
