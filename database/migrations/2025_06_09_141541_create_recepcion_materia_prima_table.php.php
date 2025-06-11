<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recepcion_materia_prima', function (Blueprint $table) {
            $table->id(); // bigIncrements (PK)
            $table->unsignedBigInteger('orden_compra_id'); // FK a orden_compra.id
            $table->unsignedBigInteger('materia_prima_id'); // FK a materias_primas.id
            $table->decimal('cantidad_recibida', 10, 2);
            $table->date('fecha_recepcion');
            $table->string('numero_lote_proveedor')->nullable(); // Lote del proveedor
            $table->unsignedBigInteger('recibido_por_user_id'); // FK a users.id
            $table->enum('estado_recepcion', ['Pendiente', 'Completa', 'Parcial', 'Rechazada'])->default('Pendiente'); // OPCIONAL: Si la necesitas
            $table->text('observaciones')->nullable(); // OPCIONAL: Si la necesitas
            $table->timestamps();

            $table->foreign('orden_compra_id')->references('id')->on('orden_compra')->onDelete('restrict');
            $table->foreign('materia_prima_id')->references('id')->on('materias_primas')->onDelete('restrict');
            $table->foreign('recibido_por_user_id')->references('id')->on('users')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recepcion_materia_prima');
    }
};
