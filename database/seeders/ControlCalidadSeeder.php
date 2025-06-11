<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ControlCalidad;
use App\Models\Lote;
use App\Models\User;
use Carbon\Carbon; // Para manejar fechas

class ControlCalidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar usuarios necesarios
        $supervisorCalidadUser = User::where('email', 'pedro.calidad@example.com')->first();
        $jefeProduccionUser = User::where('email', 'juan.produccion@example.com')->first();
        // Nota: 'operarioUser' no está definido en tu código, si lo necesitas, búscalo.
        // Por ahora, usaremos a jefeProduccionUser para 'registrado_por_user_id' si es necesario.

        // Validar la existencia de los usuarios
        if (!$supervisorCalidadUser) {
            $this->command->error('Error: El usuario "pedro.calidad@example.com" no se encontró. Asegúrate de ejecutar UserSeeder primero.');
            return;
        }
        if (!$jefeProduccionUser) {
            $this->command->error('Error: El usuario "juan.produccion@example.com" no se encontró. Asegúrate de ejecutar UserSeeder primero.');
            return;
        }


        // Buscar los lotes existentes. Es crucial que LoteSeeder se haya ejecutado antes.
        // Asegúrate de que los nombres de los productos aquí coincidan *exactamente* con los de ProductoTerminadoSeeder
        $loteEmpanada = Lote::whereHas('productoTerminado', function ($query) {
            $query->where('nombre_producto', 'Empanada_de_Carne'); // Usar el nombre corregido del producto
        })->first();

        $loteMiniArepa = Lote::whereHas('productoTerminado', function ($query) {
            $query->where('nombre_producto', 'Mini_Arepa_Reina_Pepiada'); // Usar el nombre corregido del producto
        })->first();

        $loteTequeno = Lote::whereHas('productoTerminado', function ($query) {
            $query->where('nombre_producto', 'Tequeño_Clasico'); // Usar el nombre corregido del producto (sin tilde)
        })->first();


        // Validaciones para evitar el error "Attempt to read property "id" on null"
        if (!$loteEmpanada) {
            $this->command->error('Error: No se encontró un lote para "Empanada_de_Carne". Asegúrate de que LoteSeeder haya creado este lote.');
            return;
        }
        if (!$loteMiniArepa) {
            $this->command->error('Error: No se encontró un lote para "Mini_Arepa_Reina_Pepiada". Asegúrate de que LoteSeeder haya creado este lote.');
            return;
        }
        if (!$loteTequeno) {
            $this->command->error('Error: No se encontró un lote para "Tequeño_Clasico". Asegúrate de que LoteSeeder haya creado este lote.');
            return;
        }


        // Control de calidad para el lote de Empanada de Carne (aprobado)
        ControlCalidad::create([
            'lote_id' => $loteEmpanada->id,
            'fecha_control' => Carbon::now(),
            'observaciones' => 'Textura y sabor conformes. Apariencia ligeramente irregular.',
            'resultado' => 'Aprobado', // Coincide con el ENUM de la migración (A mayúscula)
            'supervisado_por_user_id' => $supervisorCalidadUser->id,
        ]);

        // Control de calidad para el lote de Mini Arepa (aprobado)
        ControlCalidad::create([
            'lote_id' => $loteMiniArepa->id,
            'fecha_control' => Carbon::now()->addHours(1),
            'observaciones' => 'Cumple con todos los estándares de calidad. Excelente sabor y consistencia.',
            'resultado' => 'Aprobado', // Coincide con el ENUM de la migración (A mayúscula)
            'supervisado_por_user_id' => $supervisorCalidadUser->id,
        ]);

        // Control de calidad para el lote de Tequeño (pendiente de revisión o podría ser rechazado para ejemplo)
        ControlCalidad::create([
            'lote_id' => $loteTequeno->id,
            'fecha_control' => Carbon::now()->addHours(2),
            'observaciones' => 'Pendiente de segunda revisión, el color de la masa es un poco pálido.',
            'resultado' => 'Rechazado', // Para ejemplificar un lote rechazado
            'supervisado_por_user_id' => $supervisorCalidadUser->id,
        ]);

        // Si quieres simular un lote rechazado que NO está en LoteSeeder,
        // lo más limpio es crearlo en LoteSeeder con estado 'Rechazado' o 'Pendiente'
        // y luego hacer el ControlCalidad para él.
        // Evita crear lotes en este seeder si su propósito es solo el control de calidad.
    }
}
