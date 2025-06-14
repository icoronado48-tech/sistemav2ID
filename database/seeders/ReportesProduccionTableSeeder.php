<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ReporteProduccion; // Importa el modelo
use App\Models\User;             // Importa User
use App\Models\OrdenProduccion;  // Importa OrdenProduccion
use App\Models\Role;             // Importa Role
use Faker\Factory as Faker;       // Importa Faker
use Carbon\Carbon;                // Para trabajar con fechas

class ReportesProduccionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ReporteProduccion::truncate(); // Limpiar la tabla

        $faker = Faker::create('es_ES');

        $produccionUserIds = User::whereHas('role', function ($query) {
            $query->whereIn('nombre_rol', ['Jefe de Producción', 'administrador']);
        })->pluck('id')->toArray();
        $ordenProduccionIds = OrdenProduccion::pluck('id')->toArray();

        if (empty($produccionUserIds) || empty($ordenProduccionIds)) {
            echo "Advertencia: No se encontraron usuarios o órdenes de producción para ReportesProduccionTableSeeder.\n";
            return;
        }

        for ($i = 0; $i < 30; $i++) { // Crear 30 reportes de producción
            $fechaReporte = $faker->dateTimeBetween('-3 months', 'now');
            $orden = OrdenProduccion::find($faker->randomElement($ordenProduccionIds));

            ReporteProduccion::create([
                'generado_por_user_id' => $faker->randomElement($produccionUserIds),
                'fecha_reporte' => $fechaReporte,
                'tipo_reporte' => $faker->randomElement(['Diario', 'Semanal', 'Mensual']),
                'contenido_reporte' => $faker->paragraph(3), //  
                'observaciones' => $faker->optional()->sentence(4),
            ]);
        }
    }
}
