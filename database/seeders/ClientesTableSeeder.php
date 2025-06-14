<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cliente; // Importa el modelo Cliente
use Faker\Factory as Faker; // Importa Faker

class ClientesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Cliente::truncate(); // Limpiar la tabla

        $faker = Faker::create('es_ES');

        for ($i = 0; $i < 30; $i++) { // Crear 30 clientes de ejemplo
            Cliente::create([
                'nombre_cliente' => $faker->unique()->company,
                'cedula_rif' => $faker->randomElement(['V', 'E', 'J', 'G']) . '-' . $faker->numberBetween(10000000, 29999999),
                'telefono' => $faker->phoneNumber,
                'email' => $faker->unique()->safeEmail,
                'direccion' => $faker->address,
            ]);
        }
    }
}
