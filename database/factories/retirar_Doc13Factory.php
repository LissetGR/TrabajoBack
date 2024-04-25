<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\retirar_Doc13>
 */
class retirar_Doc13Factory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fecha_Procura' => $this->faker->date(),
            'fecha_Matrimonio' => $this->faker->date(), 
        ];
    }
}
