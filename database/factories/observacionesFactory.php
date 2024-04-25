<?php

namespace Database\Factories;
use App\Models\Matrimonio;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\observaciones>
 */
class observacionesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'descripcion' => $this->faker->sentence(),
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
