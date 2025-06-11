<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductoTerminado;

class ProductoTerminadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductoTerminado::create([
            'nombre_producto' => 'Empanada_de_Carne',
            'descripcion' => 'Empanadas_rellenas_de_carne_molida_sazonada.',
            'unidad_medida_salida' => 'unidad',
            'stock_actual' => 1000.00,
        ]);

        ProductoTerminado::create([
            'nombre_producto' => 'Mini_Arepa_Reina_Pepiada',
            'descripcion' => 'Min_arepas_rellenas_de_pollo_y_aguacate.',
            'unidad_medida_salida' => 'unidad',
            'stock_actual' => 750.00,
        ]);

        ProductoTerminado::create([
            'nombre_producto' => 'Tequeño_Clasico', // CORREGIDO: "Clasico" sin tilde
            'descripcion' => 'Deditos_de_queso_envueltos_en_masa_crujiente.',
            'unidad_medida_salida' => 'unidad',
            'stock_actual' => 1200.00,
        ]);
    }
}
