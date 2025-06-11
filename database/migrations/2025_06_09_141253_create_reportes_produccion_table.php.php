<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reportes_produccion', function (Blueprint $table) {
            $table->id(); // bigIncrements (PK)
            $table->date('fecha_reporte');
            $table->string('tipo_reporte'); // Ej: 'diario', 'semanal', 'mensual', 'lote'
            $table->text('contenido_reporte'); // Almacenar el contenido del reporte, quizás en JSON o texto
            $table->string('ruta_archivo_generado')->nullable(); // OPCIONAL: Si la necesitas
            $table->enum('estado', ['generado', 'fallido'])->default('generado'); // OPCIONAL: Si la necesitas
            $table->text('observaciones')->nullable(); // OPCIONAL: Si la necesitas
            $table->unsignedBigInteger('generado_por_user_id'); // FK a users.id
            $table->timestamps();

            $table->foreign('generado_por_user_id')->references('id')->on('users')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reportes_produccion');
    }
};
