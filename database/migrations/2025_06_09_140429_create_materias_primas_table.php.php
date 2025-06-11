<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materias_primas', function (Blueprint $table) {
            $table->id(); // bigIncrements (PK)
            $table->string('nombre');
            $table->string('unidad_medida');
            $table->decimal('stock_actual', 10, 2); // Decimal
            $table->decimal('stock_minimo', 10, 2); // Decimal
            $table->unsignedBigInteger('proveedor_id'); // FK a proveedores.id
            $table->timestamps();

            $table->foreign('proveedor_id')->references('id')->on('proveedores')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materias_primas');
    }
};
