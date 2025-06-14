<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ControlCalidad; // Importa el modelo
use App\Models\Lote;             // Importa el modelo Lote
use App\Models\User;            // Importa el modelo User
use App\Models\Role;            // Importa el modelo Role
use Faker\Factory as Faker;      // Importa Faker
use Carbon\Carbon;               // Para trabajar con fechas

class ControlesCalidadTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ControlCalidad::truncate(); // Limpiar la tabla

        $faker = Faker::create('es_ES');

        $loteIds = Lote::pluck('id')->toArray();
        // Usuarios que pueden realizar controles de calidad
        $calidadUserIds = User::whereHas('role', function ($query) {
            $query->whereIn('nombre_rol', ['Supervisor de Calidad', 'administrador']);
        })->pluck('id')->toArray();

        if (empty($loteIds) || empty($calidadUserIds)) {
            echo "Advertencia: No se encontraron lotes o usuarios de calidad para ControlesCalidadTableSeeder.\n";
            return;
        }

        $resultados = ['Aprobado', 'Rechazado'];


        // Crear un control de calidad para algunos lotes
        foreach ($loteIds as $loteId) {
            if ($faker->boolean(70)) { // 70% de probabilidad de tener un control de calidad
                $resultado = $faker->randomElement($resultados);
                ControlCalidad::create([
                    'lote_id' => $loteId,
                    'supervisado_por_user_id' => $faker->randomElement($calidadUserIds),
                    'fecha_control' => $faker->dateTimeBetween('-1 month', 'now'),
                    'resultado' => $resultado,
                    'observaciones' => $faker->optional()->paragraph(1),

                ]);

                // Actualizar el estado_calidad del lote basado en el resultado del control
                Lote::where('id', $loteId)->update(['estado_calidad' => $resultado]);
            }
        }
    }
}
