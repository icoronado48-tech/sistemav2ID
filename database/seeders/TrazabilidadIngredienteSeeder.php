<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TrazabilidadIngrediente;
use App\Models\Lote;
use App\Models\MateriaPrima;
use Carbon\Carbon; // Para manejar fechas

class TrazabilidadIngredienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $loteEmpanada = Lote::whereHas('productoTerminado', function ($query) {
            $query->where('nombre_producto', 'Empanada de Carne');
        })->first();

        $loteMiniArepa = Lote::whereHas('productoTerminado', function ($query) {
            $query->where('nombre_producto', 'Mini Arepa Reina Pepiada');
        })->first();

        $harina = MateriaPrima::where('nombre', 'Harina de Trigo')->first();
        $carne = MateriaPrima::where('nombre', 'Carne de Res Molida')->first();
        $sal = MateriaPrima::where('nombre', 'Sal')->first();
        $pollo = MateriaPrima::where('nombre', 'Pollo Desmechado')->first();
        $aguacate = MateriaPrima::where('nombre', 'Aguacate')->first();

        // Trazabilidad para el lote de Empanada de Carne
        if ($loteEmpanada && $harina) {
            TrazabilidadIngrediente::create([
                'lote_id' => $loteEmpanada->id,
                'materia_prima_id' => $harina->id,
                'cantidad_utilizada' => 25.00, // Kg de harina para 500 empanadas (50g * 500)
                'lote_materia_prima' => 'HAR20250515-001',
                'fecha_registro' => Carbon::now(),
            ]);
        }
        if ($loteEmpanada && $carne) {
            TrazabilidadIngrediente::create([
                'lote_id' => $loteEmpanada->id,
                'materia_prima_id' => $carne->id,
                'cantidad_utilizada' => 15.00, // Kg de carne para 500 empanadas (30g * 500)
                'lote_materia_prima' => 'CAR20250520-002',
                'fecha_registro' => Carbon::now(),
            ]);
        }
        if ($loteEmpanada && $sal) {
            TrazabilidadIngrediente::create([
                'lote_id' => $loteEmpanada->id,
                'materia_prima_id' => $sal->id,
                'cantidad_utilizada' => 0.50, // Kg de sal para 500 empanadas (1g * 500)
                'lote_materia_prima' => 'SAL20250401-003',
                'fecha_registro' => Carbon::now(),
            ]);
        }

        // Trazabilidad para el lote de Mini Arepa Reina Pepiada
        if ($loteMiniArepa && $harina) {
            TrazabilidadIngrediente::create([
                'lote_id' => $loteMiniArepa->id,
                'materia_prima_id' => $harina->id,
                'cantidad_utilizada' => 9.00, // Kg de harina para 300 mini arepas (30g * 300)
                'lote_materia_prima' => 'HAR20250515-001',
                'fecha_registro' => Carbon::now(),
            ]);
        }
        if ($loteMiniArepa && $pollo) {
            TrazabilidadIngrediente::create([
                'lote_id' => $loteMiniArepa->id,
                'materia_prima_id' => $pollo->id,
                'cantidad_utilizada' => 7.50, // Kg de pollo para 300 mini arepas (25g * 300)
                'lote_materia_prima' => 'POL20250601-001',
                'fecha_registro' => Carbon::now(),
            ]);
        }
        if ($loteMiniArepa && $aguacate) {
            TrazabilidadIngrediente::create([
                'lote_id' => $loteMiniArepa->id,
                'materia_prima_id' => $aguacate->id,
                'cantidad_utilizada' => 4.50, // Kg de aguacate para 300 mini arepas (15g * 300)
                'lote_materia_prima' => 'AGU20250605-001',
                'fecha_registro' => Carbon::now(),
            ]);
        }
    }
}
