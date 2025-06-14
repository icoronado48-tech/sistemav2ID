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
        // Tabla ajuste_inventario
        Schema::table('ajuste_inventario', function (Blueprint $table) {
            // Eliminar clave foránea existente para materia_prima_id
            $table->dropForeign(['materia_prima_id']);
            // Re-añadir con ON DELETE SET NULL
            $table->foreign('materia_prima_id')
                ->references('id')->on('materias_primas')
                ->onDelete('set null');

            // Eliminar clave foránea existente para producto_terminado_id
            $table->dropForeign(['producto_terminado_id']);
            // Re-añadir con ON DELETE SET NULL
            $table->foreign('producto_terminado_id')
                ->references('id')->on('producto_terminados')
                ->onDelete('set null');
        });

        // Tabla stock_alertas
        Schema::table('stock_alertas', function (Blueprint $table) {
            // Eliminar clave foránea existente para materia_prima_id
            $table->dropForeign(['materia_prima_id']);
            // Re-añadir con ON DELETE SET NULL
            $table->foreign('materia_prima_id')
                ->references('id')->on('materias_primas')
                ->onDelete('set null');

            // Eliminar clave foránea existente para producto_terminado_id
            $table->dropForeign(['producto_terminado_id']);
            // Re-añadir con ON DELETE SET NULL
            $table->foreign('producto_terminado_id')
                ->references('id')->on('producto_terminados')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tabla ajuste_inventario
        Schema::table('ajuste_inventario', function (Blueprint $table) {
            // Revertir a la versión original (ON DELETE CASCADE)
            $table->dropForeign(['materia_prima_id']);
            $table->foreign('materia_prima_id')
                ->references('id')->on('materias_primas')
                ->onDelete('cascade');

            $table->dropForeign(['producto_terminado_id']);
            $table->foreign('producto_terminado_id')
                ->references('id')->on('producto_terminados')
                ->onDelete('cascade');
        });

        // Tabla stock_alertas
        Schema::table('stock_alertas', function (Blueprint $table) {
            // Revertir a la versión original (ON DELETE CASCADE)
            $table->dropForeign(['materia_prima_id']);
            $table->foreign('materia_prima_id')
                ->references('id')->on('materias_primas')
                ->onDelete('cascade');

            $table->dropForeign(['producto_terminado_id']);
            $table->foreign('producto_terminado_id')
                ->references('id')->on('producto_terminados')
                ->onDelete('cascade');
        });
    }
};
