<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MateriaPrima;
use App\Models\Proveedor; // Necesitas el modelo Proveedor para obtener IDs

class MateriaPrimaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $proveedorHarinera = Proveedor::where('nombre_proveedor', 'Distribuidora Harinera S.A.')->first();
        $proveedorCarnes = Proveedor::where('nombre_proveedor', 'Carnes del Llano C.A.')->first();
        $proveedorLacteos = Proveedor::where('nombre_proveedor', 'Lácteos Puros')->first();

        MateriaPrima::create([
            'nombre' => 'Harina de Trigo',
            'unidad_medida' => 'kg',
            'stock_actual' => 500.00,
            'stock_minimo' => 100.00,
            'proveedor_id' => $proveedorHarinera->id,
        ]);

        MateriaPrima::create([
            'nombre' => 'Carne de Res Molida',
            'unidad_medida' => 'kg',
            'stock_actual' => 250.00,
            'stock_minimo' => 50.00,
            'proveedor_id' => $proveedorCarnes->id,
        ]);

        MateriaPrima::create([
            'nombre' => 'Sal',
            'unidad_medida' => 'kg',
            'stock_actual' => 50.00,
            'stock_minimo' => 10.00,
            'proveedor_id' => $proveedorHarinera->id, // Asumimos que también provee sal
        ]);

        MateriaPrima::create([
            'nombre' => 'Pollo Desmechado',
            'unidad_medida' => 'kg',
            'stock_actual' => 150.00,
            'stock_minimo' => 30.00,
            'proveedor_id' => $proveedorCarnes->id,
        ]);

        MateriaPrima::create([
            'nombre' => 'Aguacate',
            'unidad_medida' => 'kg',
            'stock_actual' => 80.00,
            'stock_minimo' => 20.00,
            'proveedor_id' => $proveedorHarinera->id, // Podría ser otro proveedor de vegetales
        ]);

        MateriaPrima::create([
            'nombre' => 'Queso Blanco Rallado',
            'unidad_medida' => 'kg',
            'stock_actual' => 120.00,
            'stock_minimo' => 25.00,
            'proveedor_id' => $proveedorLacteos->id,
        ]);
    }
}
