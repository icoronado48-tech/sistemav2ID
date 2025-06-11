<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('producto_terminados', function (Blueprint $table) {
            $table->id(); // bigIncrements (PK)
            $table->string('nombre_producto');
            $table->string('descripcion')->nullable();
            $table->string('unidad_medida_salida');
            $table->decimal('stock_actual', 10, 2); // Decimal
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('producto_terminados');
    }
};
