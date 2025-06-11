<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cliente; // Importa el modelo Cliente

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cliente::create([
            'nombre_cliente' => 'Supermercado Central',
            'cedula_rif' => 'J-001022830',
            'contacto' => 'Ana Torres',
            'telefono' => '0212-7890123',
            'email' => 'ana.torres@supercentral.com',
            'direccion' => 'Calle El Sol, Urb. Los Robles, Caracas',
        ]);

        Cliente::create([
            'nombre_cliente' => 'Panadería La Espiga Dorada',
            'contacto' => 'Pedro Linares',
            'cedula_rif' => 'J-001022835',
            'telefono' => '0241-4567890',
            'email' => 'pedro@laespigadorada.com',
            'direccion' => 'Av. Bolívar, Centro, Valencia',
        ]);

        Cliente::create([
            'nombre_cliente' => 'Cantina Universitaria UCV',
            'contacto' => 'Sofia Mendez',
            'cedula_rif' => 'J-001022834',
            'telefono' => '0212-3334455',
            'email' => 'cantina@ucv.edu.ve',
            'direccion' => 'Ciudad Universitaria, Caracas',
        ]);
    }
}
