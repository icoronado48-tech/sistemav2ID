<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Proveedor; // Importa el modelo Proveedor
use Faker\Factory as Faker; // Importa Faker

class ProveedoresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Proveedor::truncate(); // Limpiar la tabla

        $faker = Faker::create('es_ES');

        for ($i = 0; $i < 20; $i++) { // Crear 20 proveedores de ejemplo
            Proveedor::create([
                'nombre_proveedor' => $faker->unique()->company,
                'contacto' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'telefono' => $faker->phoneNumber,
                'direccion' => $faker->address,
            ]);
        }
    }
}
