<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // Asegúrate de importar tu modelo User
use App\Models\Role; // Asegúrate de importar tu modelo Role
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Asegúrate de que los roles existan primero
        $gerenteRole = Role::firstWhere('nombre_rol', 'Gerente General');
        $jefeProduccionRole = Role::firstWhere('nombre_rol', 'Jefe de Producción');
        $supervisorLogisticaRole = Role::firstWhere('nombre_rol', 'Supervisor de Logística');
        $gerenteVentasRole = Role::firstWhere('nombre_rol', 'Gerente de Ventas');
        $supervisorCalidadRole = Role::firstWhere('nombre_rol', 'Supervisor de Calidad');
        $operarioProduccionRole = Role::firstWhere('nombre_rol', 'Operario de Producción');
        $usuarioInventarioRole = Role::firstWhere('nombre_rol', 'Usuario de Inventario');

        User::firstOrCreate(
            ['email' => 'gerente@deligestion.com'],
            ['name' => 'Gerente General', 'password' => Hash::make('password'), 'role_id' => $gerenteRole->id]
        );

        User::firstOrCreate(
            ['email' => 'jefeproduccion@deligestion.com'],
            ['name' => 'Jefe Produccion', 'password' => Hash::make('password'), 'role_id' => $jefeProduccionRole->id]
        );

        User::firstOrCreate(
            ['email' => 'operario@deligestion.com'],
            ['name' => 'Operario Produccion', 'password' => Hash::make('password'), 'role_id' => $operarioProduccionRole->id]
        );

        User::firstOrCreate(
            ['email' => 'supervisorlogistica@deligestion.com'],
            ['name' => 'Supervisor Logistica', 'password' => Hash::make('password'), 'role_id' => $supervisorLogisticaRole->id]
        );

        User::firstOrCreate(
            ['email' => 'usuarioinventario@deligestion.com'],
            ['name' => 'Usuario Inventario', 'password' => Hash::make('password'), 'role_id' => $usuarioInventarioRole->id]
        );

        User::firstOrCreate(
            ['email' => 'gerenteventas@deligestion.com'],
            ['name' => 'Gerente Ventas', 'password' => Hash::make('password'), 'role_id' => $gerenteVentasRole->id]
        );

        User::firstOrCreate(
            ['email' => 'supervisorcalidad@deligestion.com'],
            ['name' => 'Supervisor Calidad', 'password' => Hash::make('password'), 'role_id' => $supervisorCalidadRole->id]
        );

        // También puedes crear datos de prueba para Materias Primas, Productos Terminados, Órdenes de Producción, etc.
        // para que el dashboard tenga algo que mostrar.
    }
}
