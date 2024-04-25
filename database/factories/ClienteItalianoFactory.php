<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Cliente;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClienteItaliano>
 */
class ClienteItalianoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email_registro' => $this->faker->unique()->safeEmail,
            'id' => Cliente::factory()->create()->id,
        ];
    }
}
