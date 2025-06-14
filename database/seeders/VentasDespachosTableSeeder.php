<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\VentaDespacho; // Importa el modelo
use App\Models\Cliente;       // Importa Cliente
use App\Models\User;         // Importa User
use App\Models\Role;         // Importa Role
use Faker\Factory as Faker;   // Importa Faker
use Carbon\Carbon;            // Para trabajar con fechas

class VentasDespachosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VentaDespacho::truncate(); // Limpiar la tabla

        $faker = Faker::create('es_ES');

        $clienteIds = Cliente::pluck('id')->toArray();
        $userVentasIds = User::whereHas('role', function ($query) {
            $query->whereIn('nombre_rol', ['Gerente de Ventas', 'administrador']);
        })->pluck('id')->toArray();

        if (empty($clienteIds) || empty($userVentasIds)) {
            echo "Advertencia: No se encontraron clientes o usuarios de ventas/admin para VentasDespachosTableSeeder.\n";
            return;
        }

        $tiposDocumento = ['Factura', 'Nota de Entrega', 'Pedido'];
        $estadosDespacho = ['Pendiente', 'Despachado Parcial', 'Despachado Completo', 'Cancelado'];

        for ($i = 0; $i < 50; $i++) { // Crear 50 ventas/despachos
            $estado = $faker->randomElement($estadosDespacho);
            $fechaVenta = $faker->dateTimeBetween('-4 months', 'now');

            VentaDespacho::create([
                'cliente_id' => $faker->randomElement($clienteIds),
                'fecha_venta_despacho' => $fechaVenta,
                'tipo_documento' => $faker->randomElement($tiposDocumento),
                'numero_documento' => 'VD-' . strtoupper($faker->unique()->bothify('###??#')),
                'estado_despacho' => $estado,
                'registrado_por_user_id' => $faker->randomElement($userVentasIds),
                'observaciones' => $faker->optional()->paragraph(1),
            ]);
        }
    }
}
