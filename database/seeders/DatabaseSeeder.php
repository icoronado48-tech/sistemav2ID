<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class, // Primero los roles
            UserSeeder::class, // Luego los usuarios que dependen de los roles
            ProveedorSeeder::class, // Proveedores
            ClienteSeeder::class, // Clientes
            MateriaPrimaSeeder::class, // Materias Primas (depende de Proveedores)
            ProductoTerminadoSeeder::class, // Productos Terminados
            RecetaSeeder::class, // Recetas (depende de ProductoTerminado)
            RecetaIngredienteSeeder::class, // Ingredientes de Recetas (depende de Receta y MateriaPrima)
            OrdenProduccionSeeder::class, // Depende de ProductoTerminado y User
            LoteSeeder::class, // Depende de OrdenProduccion, ProductoTerminado y User
            TrazabilidadIngredienteSeeder::class, // Depende de Lote y MateriaPrima
            ControlCalidadSeeder::class, // Depende de Lote y User
            StockAlertaSeeder::class, // Depende de MateriaPrima, ProductoTerminado y User
            OrdenCompraSeeder::class, // Depende de Proveedor y User
            DetalleOrdenCompraSeeder::class, // Depende de OrdenCompra y MateriaPrima
            RecepcionMateriaPrimaSeeder::class, // Depende de OrdenCompra, MateriaPrima y User
            AjusteInventarioSeeder::class, // Depende de MateriaPrima, ProductoTerminado y User
            VentaDespachoSeeder::class, // Depende de Cliente y User
            DetalleVentaDespachoSeeder::class, // Depende de VentaDespacho y Lote
            ReporteProduccionSeeder::class, // Depende de User y posiblemente OrdenProduccion
        ]);
    }
}
