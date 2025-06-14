<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modifica la tabla detalle_orden_compra
        Schema::table('detalle_orden_compra', function (Blueprint $table) {
            // Verifica si la columna existe antes de intentar eliminarla
            if (Schema::hasColumn('detalle_orden_compra', 'subtotal')) {
                $table->dropColumn('subtotal');
            }
        });

        // Modifica la tabla orden_compra
        Schema::table('orden_compra', function (Blueprint $table) {
            // Verifica si la columna existe antes de intentar eliminarla
            if (Schema::hasColumn('orden_compra', 'total_monto')) {
                $table->dropColumn('total_monto');
            }
        });

        // Modifica la tabla ventas_despachos
        Schema::table('ventas_despachos', function (Blueprint $table) {
            // Verifica si la columna existe antes de intentar eliminarla
            if (Schema::hasColumn('ventas_despachos', 'total_monto')) {
                $table->dropColumn('total_monto');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // En el método down, se añaden de nuevo las columnas si se necesita revertir.
        // Es importante definir los tipos de datos y nullable/default según la migración original.

        // Revertir cambios en detalle_orden_compra
        Schema::table('detalle_orden_compra', function (Blueprint $table) {
            // Añadir la columna de nuevo solo si no existe
            if (!Schema::hasColumn('detalle_orden_compra', 'subtotal')) {
                $table->decimal('subtotal', 10, 2)->nullable()->after('precio_unitario');
            }
        });

        // Revertir cambios en orden_compra
        Schema::table('orden_compra', function (Blueprint $table) {
            if (!Schema::hasColumn('orden_compra', 'total_monto')) {
                $table->decimal('total_monto', 12, 2)->nullable()->after('estado');
            }
        });

        // Revertir cambios en ventas_despachos
        Schema::table('ventas_despachos', function (Blueprint $table) {
            if (!Schema::hasColumn('ventas_despachos', 'total_monto')) {
                $table->decimal('total_monto', 12, 2)->nullable()->after('numero_documento');
            }
        });
    }
};
