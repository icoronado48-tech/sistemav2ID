<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalle_venta_despacho', function (Blueprint $table) {
            $table->id(); // bigIncrements (PK)
            $table->unsignedBigInteger('venta_despacho_id'); // FK a ventas_despachos.id
            $table->unsignedBigInteger('lote_id'); // FK a lotes.id (para trazar qué lote específico de PT se vendió)
            $table->decimal('cantidad_vendida_despachada', 10, 2);
            $table->decimal('precio_unitario', 10, 2);
            $table->timestamps();

            $table->foreign('venta_despacho_id')->references('id')->on('ventas_despachos')->onDelete('cascade');
            $table->foreign('lote_id')->references('id')->on('lotes')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_venta_despacho');
    }
};
