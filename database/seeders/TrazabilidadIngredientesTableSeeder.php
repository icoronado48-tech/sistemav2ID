<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TrazabilidadIngrediente; // Importa el modelo
use App\Models\Lote;                     // Importa el modelo Lote
use App\Models\MateriaPrima;            // Importa el modelo MateriaPrima
use Faker\Factory as Faker;              // Importa Faker

class TrazabilidadIngredientesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TrazabilidadIngrediente::truncate(); // Limpiar la tabla

        $faker = Faker::create('es_ES');

        $loteIds = Lote::pluck('id')->toArray();
        $materiaPrimaIds = MateriaPrima::pluck('id')->toArray();

        if (empty($loteIds) || empty($materiaPrimaIds)) {
            echo "Advertencia: No se encontraron lotes o materias primas para TrazabilidadIngredientesTableSeeder.\n";
            return;
        }

        // Crear registros de trazabilidad para algunos lotes existentes
        foreach ($loteIds as $loteId) {
            // Cada lote usará entre 1 y 3 materias primas trazables
            $numIngredientesTrazados = $faker->numberBetween(1, 3);
            $selectedMateriaPrimas = $faker->randomElements($materiaPrimaIds, $numIngredientesTrazados);

            foreach ($selectedMateriaPrimas as $mpId) {
                TrazabilidadIngrediente::create([
                    'lote_id' => $loteId,
                    'materia_prima_id' => $mpId,
                    'cantidad_utilizada' => $faker->randomFloat(2, 0.05, 50),
                    'fecha_registro' => $faker->dateTimeBetween('-1 month', 'now'),

                ]);
            }
        }
    }
}
