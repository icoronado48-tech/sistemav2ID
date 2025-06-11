<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VentaDespacho;
use App\Models\Cliente; // Asegúrate de importar el modelo Cliente
use App\Models\User; // Asegúrate de importar el modelo User
use Carbon\Carbon;

class VentaDespachoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clienteSupermercado = Cliente::where('nombre_cliente', 'Supermercado Central')->first();
        $clientepanaderia = Cliente::where('nombre_cliente', 'Panadería La Espiga Dorada')->first();
        $userVentas = User::where('email', 'carlos.ventas@example.com')->first();

        // Validaciones
        if (!$clienteSupermercado) {
            $this->command->error('Error: Cliente "Supermercado Central" no encontrado para VentaDespachoSeeder.');
            return;
        }
        if (!$clientepanaderia) {
            $this->command->error('Error: Cliente "Panadería La Espiga Dorada" no encontrado para VentaDespachoSeeder.');
            return;
        }
        if (!$userVentas) {
            $this->command->error('Error: Usuario "carlos.ventas@example.com" no encontrado para VentaDespachoSeeder.');
            return;
        }

        VentaDespacho::create([
            'cliente_id' => $clienteSupermercado->id,
            'fecha_venta_despacho' => Carbon::now(),
            'tipo_documento' => 'Factura',
            'numero_documento' => 'FAC-20250609-001',
            'total_monto' => 1500.00,
            'estado_despacho' => 'Pendiente', // CORREGIDO: coincide con el enum del DOCX
            'registrado_por_user_id' => $userVentas->id,
            'observaciones' => 'Factura de venta a supermercado.',
        ]);

        VentaDespacho::create([
            'cliente_id' => $clientepanaderia->id,
            'fecha_venta_despacho' => Carbon::now()->subDay(),
            'tipo_documento' => 'Nota de Entrega',
            'numero_documento' => 'NE-20250608-005',
            'total_monto' => 750.00,
            'estado_despacho' => 'Despachado Completo', // CORREGIDO: coincide con el enum del DOCX
            'registrado_por_user_id' => $userVentas->id,
            'observaciones' => 'Nota de entrega para panadería.',
        ]);
    }
}
