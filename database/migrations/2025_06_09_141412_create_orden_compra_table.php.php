<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orden_compra', function (Blueprint $table) {
            $table->id(); // bigIncrements (PK)
            $table->unsignedBigInteger('proveedor_id'); // FK a proveedores.id
            $table->unsignedBigInteger('creada_por_user_id'); // FK a users.id
            $table->date('fecha_orden');
            $table->date('fecha_entrega_estimada')->nullable();
            $table->enum('estado', ['Pendiente', 'Aprobada', 'Rechazada', 'Completada'])->default('Pendiente');
            $table->decimal('total_monto', 12, 2)->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->foreign('proveedor_id')->references('id')->on('proveedores')->onDelete('restrict');
            $table->foreign('creada_por_user_id')->references('id')->on('users')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orden_compra');
    }
};
