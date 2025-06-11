<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('control_calidad', function (Blueprint $table) {
            $table->id(); // bigIncrements (PK)
            $table->unsignedBigInteger('lote_id'); // FK
            $table->unsignedBigInteger('supervisado_por_user_id'); // FK a users.id
            $table->date('fecha_control');
            // Asegúrate de que estos valores coinciden en capitalización con los usados en el seeder.
            $table->enum('resultado', ['Aprobado', 'Rechazado']);
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->foreign('lote_id')->references('id')->on('lotes')->onDelete('cascade');
            $table->foreign('supervisado_por_user_id')->references('id')->on('users')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('control_calidad');
    }
};
