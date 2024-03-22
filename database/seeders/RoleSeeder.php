<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $Admin = Role::create(['name'=> 'Admin']);
        $Trabajador = Role::create(['name'=> 'Trabajador']);
        $Cliente = Role::create(['name'=> 'Cliente']);

        Permission::create(['name'=>'eliminar'])->syncRoles($Admin);
        Permission::create(['name'=>'agregar'])->syncRoles($Admin,$Trabajador);
        Permission::create(['name'=>'modificar'])->syncRoles($Admin,$Trabajador);
        Permission::create(['name'=>'listar'])->syncRoles($Admin,$Cliente,$Trabajador);



    }
}
