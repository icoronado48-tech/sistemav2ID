<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('receta_ingredientes', function (Blueprint $table) {
            $table->id(); // bigIncrements (PK)
            $table->unsignedBigInteger('receta_id'); // FK (cambiado de recetas_id a receta_id para consistencia)
            $table->unsignedBigInteger('materia_prima_id'); // FK
            $table->decimal('cantidad_necesaria', 10, 2); // Decimal
            $table->timestamps();

            $table->foreign('receta_id')->references('id')->on('recetas')->onDelete('cascade');
            $table->foreign('materia_prima_id')->references('id')->on('materias_primas')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receta_ingredientes');
    }
};
