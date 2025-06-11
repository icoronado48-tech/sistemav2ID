<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalle_orden_compra', function (Blueprint $table) {
            $table->id(); // bigIncrements (PK)
            $table->unsignedBigInteger('orden_compra_id'); // FK a orden_compra.id
            $table->unsignedBigInteger('materia_prima_id'); // FK a materias_primas.id
            $table->decimal('cantidad', 10, 2);
            $table->decimal('precio_unitario', 10, 2);
            $table->decimal('subtotal', 10, 2)->nullable(); // <-- Si necesitas esta columna, agrégala aquí
            $table->timestamps();

            $table->foreign('orden_compra_id')->references('id')->on('orden_compra')->onDelete('cascade');
            $table->foreign('materia_prima_id')->references('id')->on('materias_primas')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_orden_compra');
    }
};
