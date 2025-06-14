<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AjusteInventario;  // Importa el modelo
use App\Models\MateriaPrima;     // Importa MateriaPrima
use App\Models\ProductoTerminado; // Importa ProductoTerminado
use App\Models\User;             // Importa User
use App\Models\Role;             // Importa Role
use Faker\Factory as Faker;       // Importa Faker
use Carbon\Carbon;                // Para trabajar con fechas

class AjustesInventarioTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AjusteInventario::truncate(); // Limpiar la tabla

        $faker = Faker::create('es_ES');

        $materiaPrimaIds = MateriaPrima::pluck('id')->toArray();
        $productoTerminadoIds = ProductoTerminado::pluck('id')->toArray();
        $userInventarioIds = User::whereHas('role', function ($query) {
            $query->whereIn('nombre_rol', ['Usuario de Inventario', 'Supervisor de Logística', 'administrador']);
        })->pluck('id')->toArray();

        if (empty($userInventarioIds) || (empty($materiaPrimaIds) && empty($productoTerminadoIds))) {
            echo "Advertencia: No se encontraron usuarios o ítems de inventario para AjustesInventarioTableSeeder.\n";
            return;
        }

        $tiposAjuste = ['Entrada', 'Salida', 'Correccion'];

        for ($i = 0; $i < 30; $i++) { // Crear 30 ajustes de inventario
            $isMateriaPrima = $faker->boolean(); // 50% de probabilidad de ser MP o PT
            $itemId = null;
            $itemType = null;

            if ($isMateriaPrima && !empty($materiaPrimaIds)) {
                $itemId = $faker->randomElement($materiaPrimaIds);
                $itemType = MateriaPrima::class;
            } elseif (!empty($productoTerminadoIds)) {
                $itemId = $faker->randomElement($productoTerminadoIds);
                $itemType = ProductoTerminado::class;
            } else {
                continue; // Si no hay IDs para ajustar, salta esta iteración
            }

            $cantidad = $faker->numberBetween(1, 100);
            $tipoAjuste = $faker->randomElement($tiposAjuste);
            $cantidadAjustada = ($tipoAjuste == 'salida') ? -$cantidad : $cantidad;

            AjusteInventario::create([
                'materia_prima_id' => ($itemType == MateriaPrima::class) ? $itemId : null,
                'producto_terminado_id' => ($itemType == ProductoTerminado::class) ? $itemId : null,
                'realizado_por_user_id' => $faker->randomElement($userInventarioIds),
                'fecha_ajuste' => $faker->dateTimeBetween('-2 months', 'now'),
                'tipo_ajuste' => $tipoAjuste,
                'cantidad_ajustada' => $cantidadAjustada,
                'motivo' => $faker->sentence(5),
            ]);
        }
    }
}
