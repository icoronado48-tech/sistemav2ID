<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StockAlerta;      // Importa el modelo
use App\Models\MateriaPrima;    // Importa MateriaPrima
use App\Models\ProductoTerminado; // Importa ProductoTerminado
use App\Models\User;            // Importa User
use App\Models\Role;            // Importa Role
use Faker\Factory as Faker;      // Importa Faker
use Carbon\Carbon;               // Para trabajar con fechas

class StockAlertasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StockAlerta::truncate(); // Limpiar la tabla

        $faker = Faker::create('es_ES');

        $materiaPrimaIds = MateriaPrima::pluck('id')->toArray();
        $productoTerminadoIds = ProductoTerminado::pluck('id')->toArray();
        $adminOrLogisticaUserIds = User::whereHas('role', function ($query) {
            $query->whereIn('nombre_rol', ['administrador', 'Supervisor de Logística', 'Usuario de Inventario']);
        })->pluck('id')->toArray();

        if (empty($adminOrLogisticaUserIds) || (empty($materiaPrimaIds) && empty($productoTerminadoIds))) {
            echo "Advertencia: No se encontraron usuarios o ítems de inventario para StockAlertasTableSeeder.\n";
            return;
        }

        $tiposAlerta = ['bajo_stock', 'proximo_vencimiento', 'stock_negativo'];
        $estadosAlerta = ['activa', 'resuelta'];

        // Crear alertas para Materias Primas con stock bajo
        foreach ($materiaPrimaIds as $mpId) {
            if ($faker->boolean(40)) { // 40% de probabilidad de generar una alerta de MP
                $materiaPrima = MateriaPrima::find($mpId);
                if ($materiaPrima && $materiaPrima->stock_actual < $materiaPrima->stock_minimo * 1.2) { // Si el stock es bajo
                    StockAlerta::create([
                        'tipo_alerta' => $faker->randomElement(['bajo_stock', 'stock_negativo']),
                        'mensaje' => 'Stock bajo de ' . $materiaPrima->nombre_materia_prima,
                        'nivel_actual' => $materiaPrima->stock_actual,
                        'nivel_minimo' => $materiaPrima->stock_minimo, // 🔥 Corrección aquí
                        'materia_prima_id' => $mpId,
                        'producto_terminado_id' => null,
                        'generado_por_user_id' => $faker->randomElement($adminOrLogisticaUserIds),
                        'fecha_alerta' => $faker->dateTimeBetween('-1 month', 'now'),
                    ]);
                }
            }
        }

        // Crear alertas para Productos Terminados con stock bajo
        foreach ($productoTerminadoIds as $ptId) {
            if ($faker->boolean(30)) { // 30% de probabilidad de generar una alerta de PT
                $productoTerminado = ProductoTerminado::find($ptId);
                if ($productoTerminado && $productoTerminado->stock_actual < $productoTerminado->stock_minimo * 1.2) {
                    StockAlerta::create([
                        'tipo_alerta' => $faker->randomElement(['bajo_stock', 'stock_negativo']),
                        'mensaje' => 'Stock bajo de ' . $productoTerminado->nombre_producto_terminado,
                        'nivel_actual' => $productoTerminado->stock_actual,
                        'nivel_minimo' => $productoTerminado->stock_minimo, // ✅ Asegura que tenga un valor
                        'materia_prima_id' => null,
                        'producto_terminado_id' => $ptId,
                        'generado_por_user_id' => $faker->randomElement($adminOrLogisticaUserIds),
                        'fecha_alerta' => $faker->dateTimeBetween('-1 month', 'now'),
                    ]);
                }
            }
        }
    }
}
