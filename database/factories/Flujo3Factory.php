<?php

namespace Database\Factories;

use App\Models\preparar_Docs31;
use App\Models\Matrimonio;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Flujo3>
 */
class Flujo3Factory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 'id_matrimonio' => Matrimonio::factory()->create()->id,
            'id_prepararDocs' => preparar_Docs31::factory()->create()->id,
            'cita_cubano' => $this->faker->date(),
            'solicitud_visado' => $this->faker->date(),
            'retiro_passport' => $this->faker->date(),
            'ultimo_Email' => $this->faker->date(),
            'observaciones' => $this->faker->sentence(),
        ];
    }
    public function withMatrimonioId($matrimonioId){
        return $this->state(function (array $attributes) use ($matrimonioId) {
            return [
                'id_matrimonio' => $matrimonioId,
            ];
        });
    }
}
