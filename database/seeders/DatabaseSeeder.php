<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Flujo1;
use App\Models\Flujo2;
use App\Models\Flujo3;
use App\Models\cuotas;
use App\Models\formaPago;
use App\Models\Matrimonio;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call(RoleSeeder::class);
        $this->call(DatosSeeder::class);

        for ($i = 0; $i < 30; $i++) {
            $matrimonio = Matrimonio::factory()->create();
            $matrimonioId = $matrimonio->numero;

            Flujo1::factory()->count(1)->withMatrimonioId($matrimonioId)->create();

            Flujo2::factory()->count(1)->withMatrimonioId($matrimonioId)->create();

            Flujo3::factory()->count(1)->withMatrimonioId($matrimonioId)->create();

            formaPago::factory()->count(1)->withMatrimonioId($matrimonioId)->create();
        }

        cuotas::factory()->count(5)->create();
    }
}
