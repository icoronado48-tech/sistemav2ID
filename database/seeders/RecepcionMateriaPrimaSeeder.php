<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RecepcionMateriaPrima;
use App\Models\OrdenCompra;
use App\Models\MateriaPrima;
use App\Models\User;
use Carbon\Carbon;

class RecepcionMateriaPrimaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Asegúrate de que la OrdenCompra con estado 'Completada' exista
        // y que las Materias Primas y el Usuario existan.
        $ordenCompraCompletada = OrdenCompra::where('estado', 'Completada')->first(); // CORREGIDO: 'estado_orden' a 'estado', 'completado' a 'Completada'

        $carne = MateriaPrima::where('nombre', 'Carne de Res Molida')->first();
        $pollo = MateriaPrima::where('nombre', 'Pollo Desmechado')->first();
        $supervisorLogistica = User::where('email', 'ana.logistica@example.com')->first();

        // Validaciones
        if (!$ordenCompraCompletada) {
            $this->command->error('Error: Orden de compra con estado "Completada" no encontrada para RecepcionMateriaPrimaSeeder.');
            return;
        }
        if (!$carne) {
            $this->command->error('Error: Materia prima "Carne de Res Molida" no encontrada para RecepcionMateriaPrimaSeeder.');
            return;
        }
        if (!$pollo) {
            $this->command->error('Error: Materia prima "Pollo Desmechado" no encontrada para RecepcionMateriaPrimaSeeder.');
            return;
        }
        if (!$supervisorLogistica) {
            $this->command->error('Error: Usuario "ana.logistica@example.com" no encontrado para RecepcionMateriaPrimaSeeder.');
            return;
        }

        if ($ordenCompraCompletada && $carne && $supervisorLogistica) {
            RecepcionMateriaPrima::create([
                'orden_compra_id' => $ordenCompraCompletada->id,
                'materia_prima_id' => $carne->id,
                'cantidad_recibida' => 100.00,
                'fecha_recepcion' => Carbon::now()->subDays(5),
                'numero_lote_proveedor' => 'LTCARNE20250601', // Coincide con la migración
                'estado_recepcion' => 'Completa', // ELIMINADO: No existe en la migración de 'recepcion_materia_prima'
                'recibido_por_user_id' => $supervisorLogistica->id,
                'observaciones' => 'Entrega conforme a lo solicitado.', // ELIMINADO: No existe en la migración de 'recepcion_materia_prima'
            ]);
        }

        if ($ordenCompraCompletada && $pollo && $supervisorLogistica) {
            RecepcionMateriaPrima::create([
                'orden_compra_id' => $ordenCompraCompletada->id,
                'materia_prima_id' => $pollo->id,
                'cantidad_recibida' => 50.00,
                'fecha_recepcion' => Carbon::now()->subDays(5),
                'numero_lote_proveedor' => 'LTPOLO20250601', // Coincide con la migración
                'estado_recepcion' => 'Completa', // ELIMINADO
                'recibido_por_user_id' => $supervisorLogistica->id,
                'observaciones' => 'Entrega conforme a lo solicitado.', // ELIMINADO
            ]);
        }
    }
}
