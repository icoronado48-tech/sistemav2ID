<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trazabilidad_ingredientes', function (Blueprint $table) {
            $table->id(); // bigIncrements (PK)
            $table->unsignedBigInteger('lote_id'); // FK
            $table->unsignedBigInteger('materia_prima_id'); // FK
            $table->decimal('cantidad_utilizada', 10, 2); // Decimal
            $table->date('fecha_registro');
            $table->timestamps();

            $table->foreign('lote_id')->references('id')->on('lotes')->onDelete('cascade');
            $table->foreign('materia_prima_id')->references('id')->on('materias_primas')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trazabilidad_ingredientes');
    }
};
