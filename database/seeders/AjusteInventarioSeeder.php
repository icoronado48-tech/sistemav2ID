<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AjusteInventario;
use App\Models\MateriaPrima;
use App\Models\ProductoTerminado;
use App\Models\User;
use Carbon\Carbon;

class AjusteInventarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $harina = MateriaPrima::where('nombre', 'Harina de Trigo')->first();
        $empanada = ProductoTerminado::where('nombre_producto', 'Empanada_de_Carne')->first(); // Asegúrate del nombre exacto
        $supervisorLogistica = User::where('email', 'ana.logistica@example.com')->first(); // Asumiendo que este es el rol que realiza ajustes

        // Validaciones
        if (!$harina) {
            $this->command->error('Error: Materia prima "Harina de Trigo" no encontrada para AjusteInventarioSeeder.');
            return;
        }
        if (!$empanada) {
            $this->command->error('Error: Producto Terminado "Empanada_de_Carne" no encontrado para AjusteInventarioSeeder.');
            return;
        }
        if (!$supervisorLogistica) {
            $this->command->error('Error: Usuario "ana.logistica@example.com" no encontrado para AjusteInventarioSeeder.');
            return;
        }


        // Ajuste por pérdida de materia prima (Salida)
        if ($harina && $supervisorLogistica) {
            AjusteInventario::create([
                'materia_prima_id' => $harina->id, // Corregido: Usar FK directa
                'producto_terminado_id' => null, // Dejar nulo para MP
                'cantidad_ajustada' => -5.00, // Corregido: 'cantidad_ajuste' a 'cantidad_ajustada'
                'tipo_ajuste' => 'Salida', // Cambiado a un valor de enum válido
                'motivo' => 'Paquete roto durante manipulación.',
                'fecha_ajuste' => Carbon::now()->subDays(2),
                'realizado_por_user_id' => $supervisorLogistica->id, // Corregido: 'ajustado_por_user_id' a 'realizado_por_user_id'
            ]);
        }

        // Ajuste por corrección de conteo de producto terminado (Entrada)
        if ($empanada && $supervisorLogistica) {
            AjusteInventario::create([
                'materia_prima_id' => null, // Dejar nulo para PT
                'producto_terminado_id' => $empanada->id, // Corregido: Usar FK directa
                'cantidad_ajustada' => 10.00, // Corregido: 'cantidad_ajuste' a 'cantidad_ajustada'
                'tipo_ajuste' => 'Entrada', // Cambiado a un valor de enum válido
                'motivo' => 'Error en el conteo de inventario anterior.',
                'fecha_ajuste' => Carbon::now()->subDay(),
                'realizado_por_user_id' => $supervisorLogistica->id, // Corregido: 'ajustado_por_user_id' a 'realizado_por_user_id'
            ]);
        }
    }
}
