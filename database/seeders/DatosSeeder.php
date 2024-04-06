<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\cliente;
use App\Models\ClienteItaliano;
use App\Models\Matrimonio;

class DatosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'lisselitaI',
            'password' => 'Lisselita7',
            'role'=>'Cliente'
        ])->assignRole('Cliente');

        $user = User::create([
            'name' => 'lisselitaC',
            'password' => 'Lisselita7',
            'role'=>'Cliente'
        ])->assignRole('Cliente');


        $cliente=cliente::create([
                "username"=>"lisselitaI",
                "nombre_apellidos"=> "leordany gvhgvytfyvghvy",
                "direccion"=> "las cadenas La palma",
                "telefono"=> 55254688,
                "email"=>"liss@gmail.com",
        ]);

        $cliente=cliente::create([
            "username"=>"lisselitaC",
            "nombre_apellidos"=> "leordany gvhgvytfyvghvy",
            "direccion"=> "las cadenas La palma",
            "telefono"=> 55254688,
            "email"=>"liss@gmail.com",
    ]);
    }

}
