<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DetalleVentaDespacho;
use App\Models\VentaDespacho;
use App\Models\Lote;
use App\Models\ProductoTerminado;

class DetalleVentaDespachoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $venta1 = VentaDespacho::where('numero_documento', 'FAC-20250609-001')->first();
        $venta2 = VentaDespacho::where('numero_documento', 'NE-20250608-005')->first();

        // Asegúrate de que estos lotes existan y tengan el producto_terminado_id correcto
        $loteEmpanada = Lote::whereHas('productoTerminado', function ($query) {
            $query->where('nombre_producto', 'Empanada de Carne');
        })->where('estado_calidad', 'aprobado')->first(); // O un lote específico que se usará para la venta

        $loteMiniArepa = Lote::whereHas('productoTerminado', function ($query) {
            $query->where('nombre_producto', 'Mini Arepa Reina Pepiada');
        })->where('estado_calidad', 'aprobado')->first();

        // Detalle para la Venta 1
        if ($venta1 && $loteEmpanada) {
            DetalleVentaDespacho::create([
                'venta_despacho_id' => $venta1->id,
                'lote_id' => $loteEmpanada->id,
                'cantidad_vendida_despachada' => 300.00,
                'precio_unitario_venta' => 2.50,
                'subtotal' => 750.00,
            ]);
        }

        // Detalle para la Venta 2
        if ($venta2 && $loteMiniArepa) {
            DetalleVentaDespacho::create([
                'venta_despacho_id' => $venta2->id,
                'lote_id' => $loteMiniArepa->id,
                'cantidad_vendida_despachada' => 200.00,
                'precio_unitario_venta' => 1.80,
                'subtotal' => 360.00,
            ]);
        }
    }
}
