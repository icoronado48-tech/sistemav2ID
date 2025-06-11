<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Proveedor; // Importa el modelo Proveedor

class ProveedorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Proveedor::create([
            'nombre_proveedor' => 'Distribuidora Harinera S.A.',
            'contacto' => 'Laura Gomez',
            'telefono' => '0212-1234567',
            'email' => 'laura@harinera.com',
            'direccion' => 'Av. Principal, Edificio Central, Caracas',
        ]);

        Proveedor::create([
            'nombre_proveedor' => 'Carnes del Llano C.A.',
            'contacto' => 'Roberto Sanchez',
            'telefono' => '0243-9876543',
            'email' => 'roberto@carnesdelllano.com',
            'direccion' => 'Carr. Nacional, Sector Los Pozos, Maracay',
        ]);

        Proveedor::create([
            'nombre_proveedor' => 'Lácteos Puros',
            'contacto' => 'Marta Rodriguez',
            'telefono' => '0251-5555555',
            'email' => 'marta@lacteospuros.com',
            'direccion' => 'Zona Industrial, Barquisimeto',
        ]);
    }
}
