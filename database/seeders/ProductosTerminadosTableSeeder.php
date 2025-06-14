<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductoTerminado; // Importa el modelo ProductoTerminado
use Faker\Factory as Faker; // Importa Faker

class ProductosTerminadosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProductoTerminado::truncate(); // Limpiar la tabla

        $faker = Faker::create('es_ES');

        $unidadesMedidaProducto = ['unidades', 'cajas', 'paquetes', 'bolsas'];

        for ($i = 0; $i < 30; $i++) { // Crear 30 productos terminados de ejemplo
            ProductoTerminado::create([
                'nombre_producto' => $faker->unique()->word . ' ' . $faker->randomElement(['bizcocho', 'galletas', 'pastel', 'pan artesanal', 'cupcake', 'muffin', 'tarta']),
                'descripcion' => $faker->sentence(6),
                'unidad_medida_salida' => $faker->randomElement($unidadesMedidaProducto),
                'stock_actual' => $faker->numberBetween(0, 500),

            ]);
        }
    }
}
