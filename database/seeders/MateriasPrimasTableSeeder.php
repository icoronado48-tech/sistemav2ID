<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MateriaPrima; // Importa el modelo MateriaPrima
use App\Models\Proveedor;    // Importa el modelo Proveedor
use Faker\Factory as Faker;  // Importa Faker

class MateriasPrimasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MateriaPrima::truncate(); // Limpiar la tabla

        $faker = Faker::create('es_ES');

        $proveedorIds = Proveedor::pluck('id')->toArray();

        if (empty($proveedorIds)) {
            echo "Advertencia: No se encontraron proveedores para MateriasPrimasTableSeeder. Asegúrate de sembrar Proveedores primero.\n";
            // O puedes salir si proveedor_id es NOT NULL
            // return;
        }

        $unidadesMedida = ['kg', 'litros', 'gramos', 'unidades', 'ml'];

        for ($i = 0; $i < 50; $i++) { // Crear 50 materias primas de ejemplo
            MateriaPrima::create([
                'nombre' => $faker->unique()->word . ' ' . $faker->randomElement(['harina', 'azúcar', 'esencia', 'colorante', 'cacao', 'sal', 'levadura', 'leche']),
                'unidad_medida' => $faker->randomElement($unidadesMedida),
                'stock_actual' => $faker->randomFloat(2, 10, 1000),
                'stock_minimo' => $faker->randomFloat(2, 1, 50),
                'proveedor_id' => !empty($proveedorIds) ? $faker->randomElement($proveedorIds) : null,
            ]);
        }
    }
}
