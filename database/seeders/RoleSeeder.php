<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role; // Importa el modelo Role

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['nombre_rol' => 'Gerente', 'descripcion' => 'Acceso total al sistema.']);
        Role::create(['nombre_rol' => 'Jefe de Produccion', 'descripcion' => 'Gestiona órdenes de producción y recetas.']);
        Role::create(['nombre_rol' => 'Operario', 'descripcion' => 'Registra producción y lotes.']);
        Role::create(['nombre_rol' => 'Supervisor de Calidad', 'descripcion' => 'Realiza controles de calidad y consultas de trazabilidad.']);
        Role::create(['nombre_rol' => 'Supervisor de Logistica', 'descripcion' => 'Gestiona inventarios y recepciones.']);
        Role::create(['nombre_rol' => 'Ventas', 'descripcion' => 'Registra ventas y despachos.']);
    }
}
