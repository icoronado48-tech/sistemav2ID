<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DetalleOrdenCompra;
use App\Models\OrdenCompra;
use App\Models\MateriaPrima;

class DetalleOrdenCompraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ordenCompra1 = OrdenCompra::where('estado', 'Pendiente')->first(); // Harinera
        $ordenCompra2 = OrdenCompra::where('estado', 'Completado')->first(); // Carnes

        $harina = MateriaPrima::where('nombre', 'Harina de Trigo')->first();
        $carne = MateriaPrima::where('nombre', 'Carne de Res Molida')->first();
        $sal = MateriaPrima::where('nombre', 'Sal')->first();
        $pollo = MateriaPrima::where('nombre', 'Pollo Desmechado')->first();

        // Detalles para Orden de Compra 1 (Harinera)
        if ($ordenCompra1 && $harina) {
            DetalleOrdenCompra::create([
                'orden_compra_id' => $ordenCompra1->id,
                'materia_prima_id' => $harina->id,
                'cantidad' => 200.00,
                'precio_unitario' => 1.50,
                'subtotal' => 300.00,
            ]);
        }
        if ($ordenCompra1 && $sal) {
            DetalleOrdenCompra::create([
                'orden_compra_id' => $ordenCompra1->id,
                'materia_prima_id' => $sal->id,
                'cantidad' => 20.00,
                'precio_unitario' => 0.80,
                'subtotal' => 16.00,
            ]);
        }

        // Detalles para Orden de Compra 2 (Carnes)
        if ($ordenCompra2 && $carne) {
            DetalleOrdenCompra::create([
                'orden_compra_id' => $ordenCompra2->id,
                'materia_prima_id' => $carne->id,
                'cantidad' => 100.00,
                'precio_unitario' => 4.20,
                'subtotal' => 420.00,
            ]);
        }
        if ($ordenCompra2 && $pollo) {
            DetalleOrdenCompra::create([
                'orden_compra_id' => $ordenCompra2->id,
                'materia_prima_id' => $pollo->id,
                'cantidad' => 50.00,
                'precio_unitario' => 3.80,
                'subtotal' => 190.00,
            ]);
        }
    }
}
