<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\OrdenProduccion;  // Importa el modelo OrdenProduccion
use App\Models\ProductoTerminado; // Importa el modelo ProductoTerminado
use App\Models\User;            // Importa el modelo User
use App\Models\Role;            // Importa el modelo Role (para buscar usuarios por rol)
use Faker\Factory as Faker;      // Importa Faker
use Carbon\Carbon;               // Para trabajar con fechas

class OrdenesProduccionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OrdenProduccion::truncate(); // Limpiar la tabla

        $faker = Faker::create('es_ES');

        $productoTerminadoIds = ProductoTerminado::pluck('id')->toArray();
        // Obtener IDs de usuarios con rol 'Jefe de Producción' u 'Operario de Producción'
        $produccionUserIds = User::whereHas('role', function ($query) {
            $query->whereIn('nombre_rol', ['Jefe de Producción', 'Operario de Producción', 'administrador']);
        })->pluck('id')->toArray();

        if (empty($productoTerminadoIds) || empty($produccionUserIds)) {
            echo "Advertencia: No se encontraron productos terminados o usuarios de producción para OrdenesProduccionTableSeeder.\n";
            return;
        }

        $estados = ['pendiente', 'en_proceso', 'completada', 'cancelada'];

        for ($i = 0; $i < 40; $i++) { // Crear 40 órdenes de producción
            $estado = $faker->randomElement($estados);
            $fechaInicio = $faker->dateTimeBetween('-3 months', 'now');
            $fechaFin = null;
            if ($estado == 'completada') {
                $fechaFin = $faker->dateTimeBetween($fechaInicio, 'now');
            } elseif ($estado == 'cancelada') {
                $fechaFin = $faker->dateTimeBetween($fechaInicio, 'now');
            }

            OrdenProduccion::create([
                'producto_terminado_id' => $faker->randomElement($productoTerminadoIds),
                'cantidad_a_producir' => $faker->numberBetween(10, 500),
                'fecha_planificada_inicio' => $faker->dateTimeBetween('-1 month', 'now'),
                'fecha_planificada_fin' => $faker->dateTimeBetween('now', '+1 month'),

                'estado' => $estado,
                'creada_por_user_id' => $faker->randomElement($produccionUserIds),
            ]);
        }
    }
}
