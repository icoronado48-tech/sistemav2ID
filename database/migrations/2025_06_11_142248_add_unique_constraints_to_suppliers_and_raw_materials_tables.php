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
        Schema::table('proveedores', function (Blueprint $table) {
            // Añadir restricción UNIQUE a nombre_proveedor
            // Verifica si ya existe antes de añadir para evitar errores en re-runs parciales
            $table->unique('nombre_proveedor');
            // Añadir restricción UNIQUE a email si es el contacto principal único
            $table->unique('email');
        });

        Schema::table('materias_primas', function (Blueprint $table) {
            // Añadir restricción UNIQUE a nombre
            $table->unique('nombre');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proveedores', function (Blueprint $table) {
            // Eliminar la restricción UNIQUE en caso de reversión
            $table->dropUnique(['nombre_proveedor']);
            $table->dropUnique(['email']);
        });

        Schema::table('materias_primas', function (Blueprint $table) {
            // Eliminar la restricción UNIQUE en caso de reversión
            $table->dropUnique(['nombre']);
        });
    }
};
