<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_alertas', function (Blueprint $table) {
            $table->id(); // bigIncrements (PK)
            $table->unsignedBigInteger('materia_prima_id')->nullable(); // Puede ser nulo si la alerta es para PT
            $table->unsignedBigInteger('producto_terminado_id')->nullable(); // Nueva columna para PT
            $table->decimal('nivel_actual', 10, 2); // Decimal
            $table->decimal('nivel_minimo', 10, 2); // Decimal
            $table->string('tipo_alerta'); // Ej: 'stock_bajo', 'vencimiento_proximo'
            $table->text('mensaje')->nullable();
            $table->boolean('resuelta')->default(false);
            $table->timestamp('fecha_alerta')->useCurrent();
            $table->unsignedBigInteger('generado_por_user_id'); // FK a users.id
            $table->timestamps();

            $table->foreign('materia_prima_id')->references('id')->on('materias_primas')->onDelete('cascade');
            $table->foreign('producto_terminado_id')->references('id')->on('producto_terminados')->onDelete('cascade');
            $table->foreign('generado_por_user_id')->references('id')->on('users')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_alertas');
    }
};
