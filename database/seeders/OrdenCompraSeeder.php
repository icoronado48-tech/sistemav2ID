<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrdenCompra;
use App\Models\Proveedor;
use App\Models\User;
use Carbon\Carbon;

class OrdenCompraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $proveedorHarinera = Proveedor::where('nombre_proveedor', 'Distribuidora Harinera S.A.')->first();
        $proveedorCarnes = Proveedor::where('nombre_proveedor', 'Carnes del Llano C.A.')->first();
        $supervisorLogistica = User::where('email', 'ana.logistica@example.com')->first();
        $gerente = User::where('email', 'admin@example.com')->first();

        // Validaciones para asegurar que los proveedores y usuarios existen
        if (!$proveedorHarinera) {
            $this->command->error('Error: Proveedor "Distribuidora Harinera S.A." no encontrado.');
            return;
        }
        if (!$proveedorCarnes) {
            $this->command->error('Error: Proveedor "Carnes del Llano C.A." no encontrado.');
            return;
        }
        if (!$supervisorLogistica) {
            $this->command->error('Error: Usuario "ana.logistica@example.com" no encontrado.');
            return;
        }
        if (!$gerente) {
            $this->command->error('Error: Usuario "admin@example.com" no encontrado.');
            return;
        }


        OrdenCompra::create([
            'proveedor_id' => $proveedorHarinera->id,
            'fecha_orden' => Carbon::now()->subDays(2),
            'estado' => 'Pendiente', // Corregido: 'estado_orden' a 'estado' y valor 'Pendiente' con P mayúscula
            'creada_por_user_id' => $supervisorLogistica->id,
            'observaciones' => 'Urgente por bajo stock de harina.',
        ]);

        OrdenCompra::create([
            'proveedor_id' => $proveedorCarnes->id,
            'fecha_orden' => Carbon::now()->subWeek(),
            'estado' => 'Completada', // Corregido: 'estado_orden' a 'estado' y valor 'Completada' con C mayúscula
            'creada_por_user_id' => $gerente->id,
            'observaciones' => 'Orden regular de carnes.',
        ]);
    }
}
