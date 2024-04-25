<?php

namespace Database\Factories;
use App\Models\Matrimonio;
use App\Models\preparar_Doc21;
use App\Models\Flujo2;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Flujo2>
 */
class Flujo2Factory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
        // 'id_matrimonio' => Matrimonio::factory(),
        'id_prepararDocs' => preparar_Doc21::factory(),
        'cita_trans' => $this->faker->date(),
        'quinto_Email' => $this->faker->date(),
        'transc_embajada' => $this->faker->date(),
        'sexto_Email' => $this->faker->date(),
        'fecha_solicVisa' => $this->faker->date(),
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
