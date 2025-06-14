<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DetalleVentaDespacho; // Importa el modelo
use App\Models\VentaDespacho;        // Importa VentaDespacho
use App\Models\Lote;                  // Importa Lote
use Faker\Factory as Faker;          // Importa Faker

class DetalleVentaDespachoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DetalleVentaDespacho::truncate(); // Limpiar la tabla

        $faker = Faker::create('es_ES');

        $ventaDespachoIds = VentaDespacho::pluck('id')->toArray();
        // Solo lotes con estado de calidad 'Aprobado' para ventas
        $loteIdsAprobados = Lote::where('estado_calidad', 'Aprobado')->pluck('id')->toArray();


        if (empty($ventaDespachoIds) || empty($loteIdsAprobados)) {
            echo "Advertencia: No se encontraron ventas/despachos o lotes aprobados para DetalleVentaDespachoTableSeeder.\n";
            return;
        }

        // Crear detalles para algunas ventas/despachos
        foreach ($ventaDespachoIds as $ventaId) {
            // Cada venta tendrá entre 1 y 3 productos
            $numProductos = $faker->numberBetween(1, 3);
            $selectedLotes = $faker->randomElements($loteIdsAprobados, $numProductos);

            foreach ($selectedLotes as $loteId) {
                $cantidad = $faker->numberBetween(1, 100);
                $precio = $faker->randomFloat(2, 5, 50); // Precio unitario de venta
                DetalleVentaDespacho::create([
                    'venta_despacho_id' => $ventaId,
                    'lote_id' => $loteId,
                    'cantidad_vendida_despachada' => $cantidad,
                    'precio_unitario' => $precio,
                ]);
            }
        }
    }
}
