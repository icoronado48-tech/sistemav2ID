<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrdenProduccion;
use App\Models\ProductoTerminado;
use App\Models\User;

class OrdenProduccionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Asegúrate de que estos productos existan antes de ejecutar este seeder.
        //    De lo contrario, 'first()' devolverá null y '->id' causará un error.
        //    Es FUNDAMENTAL que haya un seeder para ProductoTerminado que se ejecute ANTES de este.
        $empanadaCarne = ProductoTerminado::where('nombre_producto', 'Empanada_de_Carne')->first();
        $miniArepa = ProductoTerminado::where('nombre_producto', 'Mini_Arepa_Reina_Pepiada')->first();
        $tequenoClasico = ProductoTerminado::where('nombre_producto', 'Tequeño_Clasico')->first(); // AÑADIDO: Busca el tequeño clásico

        // 2. Asegúrate de que el usuario 'juan.produccion@example.com' exista.
        //    Es FUNDAMENTAL que haya un seeder para User que se ejecute ANTES de este.
        $jefeProduccionUser = User::where('email', 'juan.produccion@example.com')->first();


        // VALIDACIÓN: Verifica si los objetos no son nulos antes de usarlos
        if (!$empanadaCarne) {
            $this->command->error('Error: El producto "Empanada_de_Carne" no se encontró en la tabla producto_terminados.');
            return;
        }
        if (!$miniArepa) {
            $this->command->error('Error: El producto "Mini_Arepa_Reina_Pepiada" no se encontró en la tabla producto_terminados. Asegúrate de ejecutar ProductoTerminadoSeeder primero.');
            return;
        }
        if (!$tequenoClasico) { // AÑADIDO: Validación para Tequeño_Clasico
            $this->command->error('Error: El producto "Tequeño_Clasico" no se encontró en la tabla producto_terminados. Asegúrate de ejecutar ProductoTerminadoSeeder primero.');
            return;
        }
        if (!$jefeProduccionUser) {
            $this->command->error('Error: El usuario "juan.produccion@example.com" no se encontró en la tabla users.');
            return;
        }


        // Primera orden de producción: Empanada de Carne (en proceso)
        OrdenProduccion::create([
            'producto_terminado_id' => $empanadaCarne->id,
            'cantidad_a_producir' => 500.00,
            'fecha_planificada_inicio' => '2025-06-10',
            'fecha_planificada_fin' => '2025-06-10',
            'fecha_real_inicio' => '2025-06-10',
            'fecha_real_fin' => null, // Aún en proceso
            'estado' => 'en_proceso',
            'creada_por_user_id' => $jefeProduccionUser->id,
        ]);

        // Segunda orden de producción: Mini Arepa Reina Pepiada (completada)
        OrdenProduccion::create([
            'producto_terminado_id' => $miniArepa->id,
            'cantidad_a_producir' => 300.00,
            'fecha_planificada_inicio' => '2025-06-09',
            'fecha_planificada_fin' => '2025-06-09',
            'fecha_real_inicio' => '2025-06-09',
            'fecha_real_fin' => '2025-06-09',
            'estado' => 'completada', // CORREGIDO: "completada" en lugar de "completado"
            'creada_por_user_id' => $jefeProduccionUser->id,
        ]);

        // Tercera orden de producción: Tequeño Clásico (pendiente)
        OrdenProduccion::create([
            'producto_terminado_id' => $tequenoClasico->id, // AÑADIDO: Orden para Tequeño Clásico
            'cantidad_a_producir' => 400.00,
            'fecha_planificada_inicio' => '2025-06-11',
            'fecha_planificada_fin' => '2025-06-11',
            'fecha_real_inicio' => null, // Aún no ha iniciado
            'fecha_real_fin' => null,
            'estado' => 'pendiente',
            'creada_por_user_id' => $jefeProduccionUser->id,
        ]);
    }
}
