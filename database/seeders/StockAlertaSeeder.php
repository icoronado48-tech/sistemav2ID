<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StockAlerta;
use App\Models\MateriaPrima;
use App\Models\ProductoTerminado;
use App\Models\User;
use Carbon\Carbon;

class StockAlertaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $harina = MateriaPrima::where('nombre', 'Harina de Trigo')->first();
        $carne = MateriaPrima::where('nombre', 'Carne de Res Molida')->first();
        $empanada = ProductoTerminado::where('nombre_producto', 'Empanada_de_Carne')->first(); // Asegúrate del nombre exacto

        // Asegúrate de que los usuarios existan. Deberías tener un UserSeeder que los cree.
        $supervisorLogistica = User::where('email', 'ana.logistica@example.com')->first();
        $gerente = User::where('email', 'admin@example.com')->first(); // Asumiendo que 'admin@example.com' es un gerente


        // Validaciones para evitar errores "Attempt to read property "id" on null"
        if (!$harina) {
            $this->command->error('Error: Materia prima "Harina de Trigo" no encontrada.');
            return;
        }
        if (!$carne) {
            $this->command->error('Error: Materia prima "Carne de Res Molida" no encontrada.');
            return;
        }
        if (!$empanada) {
            $this->command->error('Error: Producto terminado "Empanada_de_Carne" no encontrado.');
            return;
        }
        if (!$supervisorLogistica) {
            $this->command->error('Error: Usuario "ana.logistica@example.com" no encontrado.');
            return;
        }
        if (!$gerente) {
            $this->command->error('Error: Usuario "admin@example.com" no encontrado.');
            return;
        }


        // Alerta de stock bajo para Materia Prima (Harina)
        StockAlerta::create([
            'materia_prima_id' => $harina->id, // Usa la FK directa
            'producto_terminado_id' => null, // Nulo para Materia Prima
            'nivel_actual' => 90.00, // Por debajo del stock_minimo (asumiendo 100.00)
            'nivel_minimo' => $harina->stock_minimo, // Obtener de la MP
            'tipo_alerta' => 'stock_bajo', // Nombre de la alerta
            'mensaje' => 'El stock de Harina de Trigo está bajo. Se requiere reabastecimiento urgente.',
            'fecha_alerta' => Carbon::now()->subHours(5),
            'resuelta' => false, // Usa 'resuelta' en lugar de 'estado'
            'generado_por_user_id' => $supervisorLogistica->id, // Usa la FK directa
        ]);

        // Alerta de stock bajo para Producto Terminado (Empanada de Carne)
        StockAlerta::create([
            'materia_prima_id' => null, // Nulo para Producto Terminado
            'producto_terminado_id' => $empanada->id, // Usa la FK directa
            'nivel_actual' => 150.00,
            'nivel_minimo' => 200.00, // Stock mínimo ficticio para PT (ajusta según tu lógica)
            'tipo_alerta' => 'stock_bajo',
            'mensaje' => 'El stock de Empanada de Carne está bajo. Considerar programar producción.',
            'fecha_alerta' => Carbon::now()->subDays(1),
            'resuelta' => false,
            'generado_por_user_id' => $supervisorLogistica->id,
        ]);

        // Una alerta resuelta para ejemplo (Materia Prima: Carne)
        StockAlerta::create([
            'materia_prima_id' => $carne->id,
            'producto_terminado_id' => null,
            'nivel_actual' => 60.00,
            'nivel_minimo' => $carne->stock_minimo,
            'tipo_alerta' => 'stock_bajo',
            'mensaje' => 'El stock de Carne de Res Molida estuvo bajo, pero ya fue reabastecido.',
            'fecha_alerta' => Carbon::now()->subDays(3),
            'resuelta' => true, // Esta alerta está resuelta
            'generado_por_user_id' => $gerente->id,
        ]);
    }
}
