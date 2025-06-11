<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ventas_despachos', function (Blueprint $table) {
            $table->id(); // bigIncrements (PK)
            $table->unsignedBigInteger('cliente_id'); // FK a clientes.id
            $table->date('fecha_venta_despacho');
            $table->enum('tipo_documento', ['Factura', 'Nota de Entrega', 'Pedido'])->default('Pedido');
            $table->string('numero_documento')->unique()->nullable();
            $table->decimal('total_monto', 12, 2)->nullable();
            $table->enum('estado_despacho', ['Pendiente', 'Despachado Parcial', 'Despachado Completo', 'Cancelado'])->default('Pendiente');
            $table->unsignedBigInteger('registrado_por_user_id'); // FK a users.id
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('restrict');
            $table->foreign('registrado_por_user_id')->references('id')->on('users')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ventas_despachos');
    }
};
