<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Lote;             // Importa el modelo Lote
use App\Models\OrdenProduccion;  // Importa el modelo OrdenProduccion
use App\Models\ProductoTerminado; // Importa el modelo ProductoTerminado
use App\Models\User;            // Importa el modelo User
use App\Models\Role;            // Importa el modelo Role
use Faker\Factory as Faker;      // Importa Faker
use Carbon\Carbon;               // Para trabajar con fecha

class LotesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Lote::truncate(); // Limpiar la tabla

        $faker = Faker::create('es_ES');

        $ordenProduccionIds = OrdenProduccion::pluck('id')->toArray();
        $productoTerminadoIds = ProductoTerminado::pluck('id')->toArray();
        // Usuarios que podrían registrar o supervisar lotes (e.g., producción, calidad)
        $userProduccionCalidadIds = User::whereHas('role', function ($query) {
            $query->whereIn('nombre_rol', ['Jefe de Producción', 'Operario de Producción', 'Supervisor de Calidad', 'administrador']);
        })->pluck('id')->toArray();

        if (empty($ordenProduccionIds) || empty($productoTerminadoIds) || empty($userProduccionCalidadIds)) {
            echo "Advertencia: No se encontraron dependencias para LotesTableSeeder (OrdenesProduccion, ProductosTerminados, Usuarios).\n";
            return;
        }

        $estadosCalidad = ['Pendiente', 'Aprobado', 'Rechazado'];

        for ($i = 0; $i < 60; $i++) { // Crear 60 lotes
            $estadoCalidad = $faker->randomElement($estadosCalidad);
            $fechaProduccion = Carbon::instance($faker->dateTimeBetween('-2 months', 'now'));
            $fechaVencimiento = $fechaProduccion->copy()->addDays($faker->numberBetween(30, 365));

            Lote::create([
                'orden_produccion_id' => $faker->randomElement($ordenProduccionIds),
                'producto_terminado_id' => $faker->randomElement($productoTerminadoIds),
                'cantidad_producida' => $faker->numberBetween(50, 1000),
                'fecha_produccion' => $fechaProduccion,
                'fecha_vencimiento' => $fechaVencimiento,
                'estado_calidad' => $estadoCalidad,
                'observaciones_calidad' => $faker->optional()->paragraph(1),
                'registrado_por_user_id' => $faker->randomElement($userProduccionCalidadIds),

            ]);
        }
    }
}
