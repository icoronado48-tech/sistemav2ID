<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lotes', function (Blueprint $table) {
            $table->id(); // bigIncrements (PK)
            $table->unsignedBigInteger('orden_produccion_id'); // FK
            $table->unsignedBigInteger('producto_terminado_id'); // FK
            $table->decimal('cantidad_producida', 10, 2); // Decimal
            $table->date('fecha_produccion');
            $table->date('fecha_vencimiento')->nullable();
            // Asegúrate de que las comillas sean rectas: 'Pendiente', 'Aprobado', 'Rechazado'
            $table->enum('estado_calidad', ['Pendiente', 'Aprobado', 'Rechazado'])->default('Pendiente');
            $table->text('observaciones_calidad')->nullable();
            $table->unsignedBigInteger('registrado_por_user_id'); // FK a users.id
            $table->timestamps();

            $table->foreign('orden_produccion_id')->references('id')->on('orden_produccion')->onDelete('cascade');
            $table->foreign('producto_terminado_id')->references('id')->on('producto_terminados')->onDelete('cascade');
            $table->foreign('registrado_por_user_id')->references('id')->on('users')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lotes');
    }
};
