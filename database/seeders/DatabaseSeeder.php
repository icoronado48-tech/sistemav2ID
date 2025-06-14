<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Necesario para deshabilitar/habilitar FK
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Deshabilitar la comprobación de claves foráneas temporalmente
        // Esto es crucial para usar truncate() en tablas con relaciones
        Schema::disableForeignKeyConstraints();

        // Llama a tus seeders en el orden de sus dependencias
        // El orden es CRÍTICO para evitar errores de clave foránea

        $this->call([
            RolesTableSeeder::class,             // 1. Roles (no depende de nada)
            UsersTableSeeder::class,             // 2. Usuarios (depende de Roles)
            ProveedoresTableSeeder::class,       // 3. Proveedores (no depende de nada)
            ClientesTableSeeder::class,          // 4. Clientes (no depende de nada)
            MateriasPrimasTableSeeder::class,    // 5. Materias Primas (depende de Proveedores)
            ProductosTerminadosTableSeeder::class, // 6. Productos Terminados (no depende de nada)
            RecetasTableSeeder::class,           // 7. Recetas (depende de Productos Terminados y Materias Primas - maneja RecetaIngredientes internamente)
            // RecetaIngredientesTableSeeder::class, // Ya se maneja dentro de RecetasTableSeeder

            OrdenesProduccionTableSeeder::class, // 8. Órdenes de Producción (depende de Productos Terminados, Users)
            LotesTableSeeder::class,             // 9. Lotes (depende de Órdenes de Producción, Productos Terminados, Users)

            TrazabilidadIngredientesTableSeeder::class, // 10. Trazabilidad Ingredientes (depende de Lotes, Materias Primas)
            ControlesCalidadTableSeeder::class,  // 11. Control de Calidad (depende de Lotes, Users)
            StockAlertasTableSeeder::class,      // 12. Stock Alertas (depende de Materias Primas, Productos Terminados, Users)
            ReportesProduccionTableSeeder::class, // 13. Reportes Producción (depende de Users, OrdenesProduccion)
            OrdenesCompraTableSeeder::class,     // 14. Órdenes de Compra (depende de Proveedores, Users)
            DetalleOrdenCompraTableSeeder::class, // 15. Detalle Orden Compra (depende de Orden de Compra, Materias Primas)
            RecepcionesMateriaPrimaTableSeeder::class, // 16. Recepción Materia Prima (depende de Orden de Compra, Materias Primas, Users)
            AjustesInventarioTableSeeder::class, // 17. Ajuste Inventario (depende de Materias Primas, Productos Terminados, Users)
            VentasDespachosTableSeeder::class,   // 18. Ventas Despachos (depende de Clientes, Users)
            DetalleVentaDespachoTableSeeder::class, // 19. Detalle Venta Despacho (depende de Ventas Despachos, Lotes)
        ]);

        // Habilitar la comprobación de claves foráneas de nuevo
        Schema::enableForeignKeyConstraints();
    }
}
