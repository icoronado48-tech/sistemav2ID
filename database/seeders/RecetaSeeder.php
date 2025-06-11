<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Receta;
use App\Models\ProductoTerminado; // Asegúrate de importar el modelo ProductoTerminado

class RecetaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Asegúrate de que estos productos existan en tu tabla 'producto_terminados'
        // antes de ejecutar este seeder.
        // Los nombres deben coincidir EXACTAMENTE con los que se usan en tu ProductoTerminadoSeeder.
        $empanadaCarne = ProductoTerminado::where('nombre_producto', 'Empanada_de_Carne')->first();
        $miniArepa = ProductoTerminado::where('nombre_producto', 'Mini_Arepa_Reina_Pepiada')->first();
        $tequeno = ProductoTerminado::where('nombre_producto', 'Tequeño_Clasico')->first(); // CORREGIDO: Sin tilde


        // Validaciones para evitar el error "Attempt to read property "id" on null"
        if (!$empanadaCarne) {
            $this->command->error('Error: El producto "Empanada_de_Carne" no se encontró en la tabla producto_terminados. Asegúrate de ejecutar ProductoTerminadoSeeder primero.');
            return;
        }
        if (!$miniArepa) {
            $this->command->error('Error: El producto "Mini_Arepa_Reina_Pepiada" no se encontró en la tabla producto_terminados. Asegúrate de ejecutar ProductoTerminadoSeeder primero.');
            return;
        }
        if (!$tequeno) {
            $this->command->error('Error: El producto "Tequeño_Clasico" no se encontró en la tabla producto_terminados. Asegúrate de ejecutar ProductoTerminadoSeeder primero.');
            return;
        }

        // Crear la primera receta
        Receta::create([
            'producto_terminado_id' => $empanadaCarne->id,
            'nombre_receta' => 'Receta Estandar Empanada de Carne',
            'descripcion' => 'Receta base para la elaboración de empanadas de carne.',
        ]);

        // Crear la segunda receta
        Receta::create([
            'producto_terminado_id' => $miniArepa->id,
            'nombre_receta' => 'Receta Estandar Mini Arepa Reina Pepiada',
            'descripcion' => 'Receta base para la elaboración de mini arepas Reina Pepiada.',
        ]);

        // Crear la tercera receta
        Receta::create([
            'producto_terminado_id' => $tequeno->id,
            'nombre_receta' => 'Receta Estandar Tequeño Clásico',
            'descripcion' => 'Receta base para la elaboración de tequeños clásicos.',
        ]);
    }
}
