<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Receta;           // Importa el modelo Receta
use App\Models\ProductoTerminado; // Importa el modelo ProductoTerminado
use App\Models\MateriaPrima;    // Importa el modelo MateriaPrima
use App\Models\RecetaIngrediente; // Importa el modelo RecetaIngrediente
use Faker\Factory as Faker;      // Importa Faker

class RecetasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Receta::truncate(); // Limpiar la tabla de recetas
        RecetaIngrediente::truncate(); // Limpiar la tabla de ingredientes de receta

        $faker = Faker::create('es_ES');

        $productoTerminadoIds = ProductoTerminado::pluck('id')->toArray();
        $materiaPrimaIds = MateriaPrima::pluck('id')->toArray();

        if (empty($productoTerminadoIds) || empty($materiaPrimaIds)) {
            echo "Advertencia: No se encontraron productos terminados o materias primas para RecetasTableSeeder. Asegúrate de sembrar estos modelos primero.\n";
            return; // No podemos crear recetas sin esto
        }

        for ($i = 0; $i < 20; $i++) { // Crear 20 recetas de ejemplo
            $receta = Receta::create([
                'producto_terminado_id' => $faker->randomElement($productoTerminadoIds),
                'nombre_receta' => 'Receta para ' . $faker->unique()->word,
                'descripcion' => $faker->paragraph(2),
            ]);

            // Añadir entre 2 y 5 ingredientes a cada receta
            $numIngredientes = $faker->numberBetween(2, 5);
            $selectedMateriaPrimas = $faker->randomElements($materiaPrimaIds, $numIngredientes);

            foreach ($selectedMateriaPrimas as $mpId) {
                RecetaIngrediente::create([
                    'receta_id' => $receta->id,
                    'materia_prima_id' => $mpId,
                    'cantidad_necesaria' => $faker->randomFloat(2, 0.1, 10), // Cantidad entre 0.1 y 10
                ]);
            }
        }
    }
}
