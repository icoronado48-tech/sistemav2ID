<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orden_produccion', function (Blueprint $table) {
            $table->id(); // bigIncrements (PK)
            $table->unsignedBigInteger('producto_terminado_id'); // FK
            $table->decimal('cantidad_a_producir', 10, 2); // Decimal
            $table->date('fecha_planificada_inicio');
            $table->date('fecha_planificada_fin');
            $table->date('fecha_real_inicio')->nullable();
            $table->date('fecha_real_fin')->nullable();
            // CORREGIDO: 'completada' en lugar de 'completado' para ser consistente con el seeder corregido.
            // Asegúrate de que las comillas sean rectas: 'pendiente', 'en_proceso', 'completada', 'cancelada'
            $table->enum('estado', ['pendiente', 'en_proceso', 'completada', 'cancelada'])->default('pendiente');
            $table->unsignedBigInteger('creada_por_user_id'); // FK a users.id
            $table->timestamps();

            $table->foreign('producto_terminado_id')->references('id')->on('producto_terminados')->onDelete('cascade');
            $table->foreign('creada_por_user_id')->references('id')->on('users')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orden_produccion');
    }
};
