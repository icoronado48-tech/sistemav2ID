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
        // Añadir una restricción UNIQUE compuesta a la tabla 'recetas'
        Schema::table('recetas', function (Blueprint $table) {
            // Verifica si el índice ya existe antes de crearlo
            $table->unique(['producto_terminado_id', 'nombre_receta'], 'recetas_producto_nombre_unique');
        });

        // Modificar la clave foránea 'materia_prima_id' en 'trazabilidad_ingredientes'
        Schema::table('trazabilidad_ingredientes', function (Blueprint $table) {
            // 1. Eliminar la clave foránea existente
            $table->dropForeign(['materia_prima_id']);

            // 2. Volver a añadir la clave foránea con ON DELETE RESTRICT
            $table->foreign('materia_prima_id')
                ->references('id')->on('materias_primas')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir la modificación de la clave foránea en 'trazabilidad_ingredientes'
        Schema::table('trazabilidad_ingredientes', function (Blueprint $table) {
            // 1. Eliminar la clave foránea modificada (ON DELETE RESTRICT)
            $table->dropForeign(['materia_prima_id']);

            // 2. Volver a añadir la clave foránea con la configuración original (ON DELETE CASCADE)
            $table->foreign('materia_prima_id')
                ->references('id')->on('materias_primas')
                ->onDelete('cascade');
        });

        // Eliminar la restricción UNIQUE compuesta de la tabla 'recetas'
        Schema::table('recetas', function (Blueprint $table) {
            // Eliminar el índice único por su nombre
            $table->dropUnique('recetas_producto_nombre_unique');
        });
    }
};
