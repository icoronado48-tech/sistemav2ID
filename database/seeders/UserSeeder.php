<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // Importa el modelo User
use App\Models\Role; // Importa el modelo Role
use Illuminate\Support\Facades\Hash; // Para hashear contraseñas

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener los roles para asignarlos a los usuarios
        $gerenteRole = Role::where('nombre_rol', 'Gerente')->first();
        $jefeProduccionRole = Role::where('nombre_rol', 'Jefe de Produccion')->first();
        $operarioRole = Role::where('nombre_rol', 'Operario')->first();
        $supervisorCalidadRole = Role::where('nombre_rol', 'Supervisor de Calidad')->first();
        $supervisorLogisticaRole = Role::where('nombre_rol', 'Supervisor de Logistica')->first();
        $ventasRole = Role::where('nombre_rol', 'Ventas')->first();

        User::create([
            'name' => 'Admin Gerente',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), // Siempre hashear contraseñas
            'role_id' => $gerenteRole->id,
        ]);

        User::create([
            'name' => 'Juan Produccion',
            'email' => 'juan.produccion@example.com',
            'password' => Hash::make('password'),
            'role_id' => $jefeProduccionRole->id,
        ]);

        User::create([
            'name' => 'Maria Operaria',
            'email' => 'maria.operaria@example.com',
            'password' => Hash::make('password'),
            'role_id' => $operarioRole->id,
        ]);

        User::create([
            'name' => 'Pedro Calidad',
            'email' => 'pedro.calidad@example.com',
            'password' => Hash::make('password'),
            'role_id' => $supervisorCalidadRole->id,
        ]);

        User::create([
            'name' => 'Ana Logistica',
            'email' => 'ana.logistica@example.com',
            'password' => Hash::make('password'),
            'role_id' => $supervisorLogisticaRole->id,
        ]);

        User::create([
            'name' => 'Carlos Ventas',
            'email' => 'carlos.ventas@example.com',
            'password' => Hash::make('password'),
            'role_id' => $ventasRole->id,
        ]);
    }
}
