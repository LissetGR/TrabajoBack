<?php

namespace Database\Factories;
use App\Models\Matrimonio;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\formaPago>
 */
class formaPagoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        
        return [
            'tipo' => $this->faker->randomElement(['Pagato totale', 'Acconto']),
            'monto_pago' => $this->faker->numberBetween(100, 10000),
            'fecha' => $this->faker->date(),
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
