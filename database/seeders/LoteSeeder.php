<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lote;
use App\Models\OrdenProduccion;
use App\Models\User;
use Carbon\Carbon; // Asegúrate de importar Carbon

class LoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Asegúrate de que los usuarios y las órdenes de producción existen
        // antes de ejecutar este seeder, y que los correos/estados coinciden.
        $jefeProduccionUser = User::where('email', 'juan.produccion@example.com')->first(); // CORREGIDO: Usar 'juan.produccion' según OrdenProduccionSeeder
        $supervisorCalidadUser = User::where('email', 'pedro.calidad@example.com')->first();

        // Asegúrate de que estas órdenes de producción existan y tengan el estado correcto
        // Lo ideal es buscar por producto_terminado_id o un ID específico si la lógica lo permite.
        // Aquí asumimos que las órdenes son las primeras que se crearon en OrdenProduccionSeeder.
        // La búsqueda por estado es frágil si tienes múltiples órdenes con el mismo estado.
        // Una mejor práctica sería pasar los IDs de las órdenes de un seeder a otro,
        // o generarlos con un orden específico y luego buscarlos.
        // Para este ejemplo, ajustamos los estados para que coincidan con la migración.
        $ordenProduccion1 = OrdenProduccion::where('estado', 'en_proceso')->first(); // Empanada de Carne
        $ordenProduccion2 = OrdenProduccion::where('estado', 'completada')->first(); // Mini Arepa Reina Pepiada
        $ordenProduccion3 = OrdenProduccion::where('estado', 'pendiente')->first(); // Tequeño Clásico (la que añadimos)


        // Validaciones para evitar el error "Attempt to read property "id" on null"
        if (!$jefeProduccionUser) {
            $this->command->error('Error: El usuario "juan.produccion@example.com" no se encontró. Asegúrate de ejecutar UserSeeder primero.');
            return;
        }
        if (!$supervisorCalidadUser) {
            $this->command->error('Error: El usuario "pedro.calidad@example.com" no se encontró. Asegúrate de ejecutar UserSeeder primero.');
            return;
        }
        if (!$ordenProduccion1) {
            $this->command->error('Error: La orden de producción en estado "en_proceso" no se encontró. Asegúrate de ejecutar OrdenProduccionSeeder primero y de que la orden exista.');
            return;
        }
        if (!$ordenProduccion2) {
            $this->command->error('Error: La orden de producción en estado "completada" no se encontró. Asegúrate de ejecutar OrdenProduccionSeeder primero y de que la orden exista.');
            return;
        }
        if (!$ordenProduccion3) { // AÑADIDO: Validación para la tercera orden
            $this->command->error('Error: La orden de producción en estado "pendiente" (Tequeño Clásico) no se encontró. Asegúrate de ejecutar OrdenProduccionSeeder primero y de que la orden exista.');
            return;
        }


        // Lote para la orden de producción de Empanada de Carne (en proceso y pendiente de calidad)
        Lote::create([
            'orden_produccion_id' => $ordenProduccion1->id,
            'producto_terminado_id' => $ordenProduccion1->producto_terminado_id,
            'cantidad_producida' => 500.00,
            'fecha_produccion' => Carbon::parse($ordenProduccion1->fecha_real_inicio)->addHours(2), // 2 horas después de iniciar OP
            'fecha_vencimiento' => Carbon::parse($ordenProduccion1->fecha_real_inicio)->addDays(30),
            'estado_calidad' => 'Pendiente', // CORREGIDO: 'Pendiente' con P mayúscula según LotesMigration
            'observaciones_calidad' => null,
            'registrado_por_user_id' => $jefeProduccionUser->id,
        ]);

        // Lote para la orden de producción de Mini Arepa (completado y aprobado)
        Lote::create([
            'orden_produccion_id' => $ordenProduccion2->id,
            'producto_terminado_id' => $ordenProduccion2->producto_terminado_id,
            'cantidad_producida' => 300.00,
            'fecha_produccion' => Carbon::parse($ordenProduccion2->fecha_real_inicio)->addHours(3),
            'fecha_vencimiento' => Carbon::parse($ordenProduccion2->fecha_real_inicio)->addDays(45),
            'estado_calidad' => 'Aprobado', // CORREGIDO: 'Aprobado' con A mayúscula según LotesMigration
            'observaciones_calidad' => 'Lote de mini arepas verificado y aprobado.',
            'registrado_por_user_id' => $jefeProduccionUser->id,
        ]);

        // Lote para la orden de producción de Tequeño (pendiente de calidad) - Nuevo lote
        Lote::create([
            'orden_produccion_id' => $ordenProduccion3->id,
            'producto_terminado_id' => $ordenProduccion3->producto_terminado_id,
            'cantidad_producida' => 400.00,
            'fecha_produccion' => Carbon::today(), // Fecha de hoy, ya que la orden está pendiente
            'fecha_vencimiento' => Carbon::today()->addDays(20),
            'estado_calidad' => 'Pendiente', // CORREGIDO: 'Pendiente' con P mayúscula según LotesMigration
            'observaciones_calidad' => null,
            'registrado_por_user_id' => $jefeProduccionUser->id,
        ]);
    }
}
