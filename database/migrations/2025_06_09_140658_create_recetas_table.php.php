<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recetas', function (Blueprint $table) {
            $table->id(); // bigIncrements (PK)
            $table->unsignedBigInteger('producto_terminado_id'); // FK
            $table->string('nombre_receta');
            $table->string('descripcion')->nullable();
            $table->timestamps();

            $table->foreign('producto_terminado_id')->references('id')->on('producto_terminados')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recetas');
    }
};
