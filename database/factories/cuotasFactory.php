<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\formaPago;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\cuotas>
 */
class cuotasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_formaPago' => FormaPago::where('tipo', 'Acconto')->inRandomOrder()->first(),
            'cantidad' => $this->faker->numberBetween(1, 100),
            'fecha' => $this->faker->date(),
        ];
    }
}
