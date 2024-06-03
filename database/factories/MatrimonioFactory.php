<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\clienteItaliano;
use App\Models\Cliente;
use App\Models\FormaPago;
use App\Models\Flujo1;
use App\Models\Flujo2;
use App\Models\Flujo3;
use App\Models\Observaciones;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Matrimonio>
 */
class MatrimonioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'numero' => $this->faker->unique()->randomNumber(4),
            'username_italiano' => Cliente::factory(),
            'username_cubano' => Cliente::factory(),
            'tipo' => $this->faker->randomElement(['Per procura', 'Congiunto']),
            'via_llegada' => $this->faker->randomElement(['Mail', 'Chiamata', 'Whatsapp', 'In busta']),
            'costo' => $this->faker->randomNumber(4),
            'fecha_llegada' => $this->faker->date(),
        ];

        // $formaPago = FormaPago::factory()->create([
        //     'id_matrimonio' => $this->faker->unique()->randomNumber(5),
        // ]);

        // $flujo1 = Flujo1::factory()->create([
        //     'id_matrimonio' => $this->faker->unique()->randomNumber(5),
        // ]);

        // $flujo2 = Flujo2::factory()->create([
        //     'id_matrimonio' => $this->faker->unique()->randomNumber(5),
        // ]);

        // $flujo3 = Flujo3::factory()->create([
        //     'id_matrimonio' => $this->faker->unique()->randomNumber(5),
        // ]);

        // $observaciones = Observaciones::factory()->create([
        //     'id_matrimonio' => $this->faker->unique()->randomNumber(5),
        // ]);
    }
}
