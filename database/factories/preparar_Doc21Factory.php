<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\preparar_Doc21>
 */
class preparar_Doc21Factory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'doc_provItalia21' => $this->faker->date(),
            'solicitud_Trans' => $this->faker->boolean(),
            'delegacion' => $this->faker->boolean(),
            'certificado_residencia' => $this->faker->boolean(),
            'doc_idItaliano' => $this->faker->boolean(),
        ];
    }
}
