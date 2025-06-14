<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\OrdenCompra; // Importa el modelo
use App\Models\Proveedor;   // Importa Proveedor
use App\Models\User;       // Importa User
use App\Models\Role;       // Importa Role
use Faker\Factory as Faker; // Importa Faker
use Carbon\Carbon;          // Para trabajar con fechas

class OrdenesCompraTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OrdenCompra::truncate(); // Limpiar la tabla

        $faker = Faker::create('es_ES');

        $proveedorIds = Proveedor::pluck('id')->toArray();
        $userLogisticaIds = User::whereHas('role', function ($query) {
            $query->whereIn('nombre_rol', ['Supervisor de Logística', 'administrador']);
        })->pluck('id')->toArray();

        if (empty($proveedorIds) || empty($userLogisticaIds)) {
            echo "Advertencia: No se encontraron proveedores o usuarios de logística/admin para OrdenesCompraTableSeeder.\n";
            return;
        }

        $estados = ['Pendiente', 'Aprobada', 'Rechazada', 'Completada'];

        for ($i = 0; $i < 40; $i++) { // Crear 40 órdenes de compra
            $estado = $faker->randomElement($estados);
            $fechaOrden = Carbon::instance($faker->dateTimeBetween('-6 months', 'now'));
            $fechaEntregaEstimada = $fechaOrden->copy()->addDays($faker->numberBetween(7, 60));

            $fechaRecepcionReal = null;
            if (in_array($estado, ['recibida_parcial', 'recibida_completa'])) {
                $fechaRecepcionReal = $faker->dateTimeBetween($fechaOrden, 'now');
            }

            OrdenCompra::create([
                'proveedor_id' => $faker->randomElement($proveedorIds),
                'creada_por_user_id' => $faker->randomElement($userLogisticaIds),
                'fecha_orden' => $fechaOrden,
                'fecha_entrega_estimada' => $fechaEntregaEstimada,
                'estado' => $estado,
                'observaciones' => $faker->optional()->paragraph(1),
            ]);
        }
    }
}
