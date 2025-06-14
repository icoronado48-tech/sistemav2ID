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
        Schema::table('lotes', function (Blueprint $table) {
            // Eliminar la clave foránea existente para producto_terminado_id
            $table->dropForeign(['producto_terminado_id']);
            // Añadir la clave foránea con ON DELETE RESTRICT
            $table->foreign('producto_terminado_id')
                ->references('id')->on('producto_terminados')
                ->onDelete('restrict');
        });

        Schema::table('orden_produccion', function (Blueprint $table) {
            // Eliminar la clave foránea existente para producto_terminado_id
            $table->dropForeign(['producto_terminado_id']);
            // Añadir la clave foránea con ON DELETE RESTRICT
            $table->foreign('producto_terminado_id')
                ->references('id')->on('producto_terminados')
                ->onDelete('restrict');
        });

        Schema::table('receta_ingredientes', function (Blueprint $table) {
            // Eliminar la clave foránea existente para materia_prima_id
            $table->dropForeign(['materia_prima_id']);
            // Añadir la clave foránea con ON DELETE RESTRICT
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
        Schema::table('lotes', function (Blueprint $table) {
            // Revertir a la versión original si es necesario (ON DELETE CASCADE)
            $table->dropForeign(['producto_terminado_id']);
            $table->foreign('producto_terminado_id')
                ->references('id')->on('producto_terminados')
                ->onDelete('cascade');
        });

        Schema::table('orden_produccion', function (Blueprint $table) {
            // Revertir a la versión original si es necesario (ON DELETE CASCADE)
            $table->dropForeign(['producto_terminado_id']);
            $table->foreign('producto_terminado_id')
                ->references('id')->on('producto_terminados')
                ->onDelete('cascade');
        });

        Schema::table('receta_ingredientes', function (Blueprint $table) {
            // Revertir a la versión original si es necesario (ON DELETE CASCADE)
            $table->dropForeign(['materia_prima_id']);
            $table->foreign('materia_prima_id')
                ->references('id')->on('materias_primas')
                ->onDelete('cascade');
        });
    }
};
