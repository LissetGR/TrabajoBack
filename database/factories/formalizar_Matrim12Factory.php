<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\formalizar_Matrim12>
 */
class formalizar_Matrim12Factory extends Factory
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
            'lugar' => $this->faker->city, 
            'tipo' => $this->faker->randomElement(['Divizioni dei beni', 'Comunidad dei beni']),
        ];
    }
}
