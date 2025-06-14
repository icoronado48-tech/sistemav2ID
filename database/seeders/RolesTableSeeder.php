<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role; // Asegúrate de importar tu modelo Role

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        Role::firstOrCreate(['nombre_rol' => 'Gerente General', 'descripcion' => 'Acceso total al sistema.']);
        Role::firstOrCreate(['nombre_rol' => 'Jefe de Producción', 'descripcion' => 'Gestiona órdenes y procesos de producción.']);
        Role::firstOrCreate(['nombre_rol' => 'Operario de Producción', 'descripcion' => 'Registra actividades diarias de producción.']);
        Role::firstOrCreate(['nombre_rol' => 'Supervisor de Logística', 'descripcion' => 'Gestiona inventarios y recepciones.']);
        Role::firstOrCreate(['nombre_rol' => 'Usuario de Inventario', 'descripcion' => 'Realiza operaciones básicas de inventario.']);
        Role::firstOrCreate(['nombre_rol' => 'Gerente de Ventas', 'descripcion' => 'Gestiona ventas y despachos.']);
        Role::firstOrCreate(['nombre_rol' => 'Supervisor de Calidad', 'descripcion' => 'Realiza controles de calidad.']);
        // Agrega más roles si los tienes
    }
}
