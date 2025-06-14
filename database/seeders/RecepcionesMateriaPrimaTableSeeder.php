<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RecepcionMateriaPrima; // Importa el modelo
use App\Models\OrdenCompra;           // Importa OrdenCompra
use App\Models\MateriaPrima;         // Importa MateriaPrima
use App\Models\User;                 // Importa User
use App\Models\Role;                 // Importa Role
use Faker\Factory as Faker;           // Importa Faker
use Carbon\Carbon;                    // Para trabajar con fechas

class RecepcionesMateriaPrimaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RecepcionMateriaPrima::truncate(); // Limpiar la tabla

        $faker = Faker::create('es_ES');

        $ordenCompraIds = OrdenCompra::pluck('id')->toArray();
        $materiaPrimaIds = MateriaPrima::pluck('id')->toArray();
        $userLogisticaIds = User::whereHas('role', function ($query) {
            $query->whereIn('nombre_rol', ['Supervisor de Logística', 'Usuario de Inventario', 'administrador']);
        })->pluck('id')->toArray();


        if (empty($ordenCompraIds) || empty($materiaPrimaIds) || empty($userLogisticaIds)) {
            echo "Advertencia: No se encontraron dependencias para RecepcionesMateriaPrimaTableSeeder.\n";
            return;
        }

        $estadosRecepcion = ['Pendiente', 'Completa', 'Parcial', 'Rechazada'];

        // Crear recepciones para algunas órdenes de compra y materias primas
        for ($i = 0; $i < 50; $i++) { // Crear 50 recepciones
            $ordenCompraId = $faker->randomElement($ordenCompraIds);
            $materiaPrimaId = $faker->randomElement($materiaPrimaIds);
            $fechaRecepcion = $faker->dateTimeBetween('-3 months', 'now');

            RecepcionMateriaPrima::create([
                'orden_compra_id' => $ordenCompraId,
                'materia_prima_id' => $materiaPrimaId,
                'cantidad_recibida' => $faker->numberBetween(5, 500),
                'fecha_recepcion' => $fechaRecepcion,
                'estado_recepcion' => $faker->randomElement($estadosRecepcion),
                'recibido_por_user_id' => $faker->randomElement($userLogisticaIds),
                'observaciones' => $faker->optional()->paragraph(1),
            ]);
        }
    }
}
