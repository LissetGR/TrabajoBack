<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\preparar_Docs31>
 */
class preparar_Docs31Factory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'doc_provItalia31' => $this->faker->date(),
            'declaracion_alojamiento' => $this->faker->boolean(),
            'reserva_aerea' => $this->faker->boolean(),
            'certificado_residenciaItaliano' => $this->faker->boolean(),
        ];
    }
}
