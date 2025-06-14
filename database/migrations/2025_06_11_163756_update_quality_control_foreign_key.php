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
        // Modificar la clave foránea 'lote_id' en 'control_calidad'
        Schema::table('control_calidad', function (Blueprint $table) {
            // 1. Eliminar la clave foránea existente (con ON DELETE CASCADE)
            $table->dropForeign(['lote_id']);

            // 2. Volver a añadir la clave foránea con ON DELETE RESTRICT
            $table->foreign('lote_id')
                ->references('id')->on('lotes')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir la modificación de la clave foránea en 'control_calidad'
        Schema::table('control_calidad', function (Blueprint $table) {
            // 1. Eliminar la clave foránea modificada (ON DELETE RESTRICT)
            $table->dropForeign(['lote_id']);

            // 2. Volver a añadir la clave foránea con la configuración original (ON DELETE CASCADE)
            $table->foreign('lote_id')
                ->references('id')->on('lotes')
                ->onDelete('cascade');
        });
    }
};
