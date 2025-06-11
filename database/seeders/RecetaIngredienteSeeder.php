<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RecetaIngrediente;
use App\Models\Receta;
use App\Models\MateriaPrima;

class RecetaIngredienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $recetaEmpanada = Receta::where('nombre_receta', 'Receta Estandar Empanada de Carne')->first();
        $recetaMiniArepa = Receta::where('nombre_receta', 'Receta Estandar Mini Arepa Reina Pepiada')->first();
        $recetaTequeno = Receta::where('nombre_receta', 'Receta Estandar Tequeño Clásico')->first();

        $harina = MateriaPrima::where('nombre', 'Harina de Trigo')->first();
        $carne = MateriaPrima::where('nombre', 'Carne de Res Molida')->first();
        $sal = MateriaPrima::where('nombre', 'Sal')->first();
        $pollo = MateriaPrima::where('nombre', 'Pollo Desmechado')->first();
        $aguacate = MateriaPrima::where('nombre', 'Aguacate')->first();
        $queso = MateriaPrima::where('nombre', 'Queso Blanco Rallado')->first();

        // Ingredientes para Empanada de Carne
        if ($recetaEmpanada && $harina && $carne && $sal) {
            RecetaIngrediente::create([
                'receta_id' => $recetaEmpanada->id,
                'materia_prima_id' => $harina->id,
                'cantidad_necesaria' => 0.05, // 50 gramos por unidad
            ]);
            RecetaIngrediente::create([
                'receta_id' => $recetaEmpanada->id,
                'materia_prima_id' => $carne->id,
                'cantidad_necesaria' => 0.03, // 30 gramos por unidad
            ]);
            RecetaIngrediente::create([
                'receta_id' => $recetaEmpanada->id,
                'materia_prima_id' => $sal->id,
                'cantidad_necesaria' => 0.001, // 1 gramo por unidad
            ]);
        }

        // Ingredientes para Mini Arepa Reina Pepiada
        if ($recetaMiniArepa && $harina && $pollo && $aguacate && $sal) {
            RecetaIngrediente::create([
                'receta_id' => $recetaMiniArepa->id,
                'materia_prima_id' => $harina->id,
                'cantidad_necesaria' => 0.03, // 30 gramos por unidad
            ]);
            RecetaIngrediente::create([
                'receta_id' => $recetaMiniArepa->id,
                'materia_prima_id' => $pollo->id,
                'cantidad_necesaria' => 0.025, // 25 gramos por unidad
            ]);
            RecetaIngrediente::create([
                'receta_id' => $recetaMiniArepa->id,
                'materia_prima_id' => $aguacate->id,
                'cantidad_necesaria' => 0.015, // 15 gramos por unidad
            ]);
            RecetaIngrediente::create([
                'receta_id' => $recetaMiniArepa->id,
                'materia_prima_id' => $sal->id,
                'cantidad_necesaria' => 0.0005, // 0.5 gramos por unidad
            ]);
        }

        // Ingredientes para Tequeño Clásico
        if ($recetaTequeno && $harina && $queso && $sal) {
            RecetaIngrediente::create([
                'receta_id' => $recetaTequeno->id,
                'materia_prima_id' => $harina->id,
                'cantidad_necesaria' => 0.02, // 20 gramos por unidad
            ]);
            RecetaIngrediente::create([
                'receta_id' => $recetaTequeno->id,
                'materia_prima_id' => $queso->id,
                'cantidad_necesaria' => 0.015, // 15 gramos por unidad
            ]);
        }
    }
}
