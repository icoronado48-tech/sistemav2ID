<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ajuste_inventario', function (Blueprint $table) {
            $table->id(); // bigIncrements (PK)
            $table->unsignedBigInteger('materia_prima_id')->nullable();
            $table->unsignedBigInteger('producto_terminado_id')->nullable();
            $table->decimal('cantidad_ajustada', 10, 2); // Positivo para aumento, negativo para disminución
            $table->enum('tipo_ajuste', ['Entrada', 'Salida', 'Correccion'])->default('Correccion');
            $table->text('motivo')->nullable();
            $table->date('fecha_ajuste');
            $table->unsignedBigInteger('realizado_por_user_id'); // FK a users.id
            $table->timestamps();

            $table->foreign('materia_prima_id')->references('id')->on('materias_primas')->onDelete('cascade');
            $table->foreign('producto_terminado_id')->references('id')->on('producto_terminados')->onDelete('cascade');
            $table->foreign('realizado_por_user_id')->references('id')->on('users')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ajuste_inventario');
    }
};
