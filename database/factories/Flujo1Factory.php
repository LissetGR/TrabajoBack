<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Matrimonio;
use App\Models\llegada_Doc11;
use App\Models\formalizar_Matrim12;
use App\Models\retirar_Doc13;
use App\Models\traduccion14;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Flujo1>
 */
class Flujo1Factory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_llegada_documentos' => llegada_Doc11::factory(),
            'primer_Email' => $this->faker->date(),
            'email_Cubano' => $this->faker->date(),
            'coordinar_Matrim' => $this->faker->date(),
            'id_formalizarMatrimonio' => formalizar_Matrim12::factory(),
            'segundo_Email' => $this->faker->date(),
            'procura_minrex' => $this->faker->date(),
            'retirada_CM' => $this->faker->date(),
            'tercer_Email' => $this->faker->date(),
            'cm_minrex' => $this->faker->date(),
            'id_retiroDocsMinrex' => retirar_Doc13::factory(),
            'cuarto_Email' => $this->faker->date(),
            'id_traduccion' => traduccion14::factory(),
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
