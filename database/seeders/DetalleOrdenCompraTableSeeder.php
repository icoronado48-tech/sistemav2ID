<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DetalleOrdenCompra; // Importa el modelo
use App\Models\OrdenCompra;        // Importa OrdenCompra
use App\Models\MateriaPrima;      // Importa MateriaPrima
use Faker\Factory as Faker;        // Importa Faker

class DetalleOrdenCompraTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DetalleOrdenCompra::truncate(); // Limpiar la tabla

        $faker = Faker::create('es_ES');

        $ordenCompraIds = OrdenCompra::pluck('id')->toArray();
        $materiaPrimaIds = MateriaPrima::pluck('id')->toArray();

        if (empty($ordenCompraIds) || empty($materiaPrimaIds)) {
            echo "Advertencia: No se encontraron órdenes de compra o materias primas para DetalleOrdenCompraTableSeeder.\n";
            return;
        }

        // Crear detalles para algunas órdenes de compra
        foreach ($ordenCompraIds as $ordenId) {
            // Cada orden tendrá entre 1 y 4 ítems
            $numItems = $faker->numberBetween(1, 4);
            $selectedMateriaPrimas = $faker->randomElements($materiaPrimaIds, $numItems);

            foreach ($selectedMateriaPrimas as $mpId) {
                $cantidad = $faker->numberBetween(10, 500);
                $precio = $faker->randomFloat(2, 1, 30);
                DetalleOrdenCompra::create([
                    'orden_compra_id' => $ordenId,
                    'materia_prima_id' => $mpId,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precio,

                ]);
            }
        }
    }
}
