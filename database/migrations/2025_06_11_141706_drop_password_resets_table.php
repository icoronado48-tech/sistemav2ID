<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Verifica si la tabla existe antes de intentar eliminarla
        if (Schema::hasTable('password_resets')) {
            Schema::dropIfExists('password_resets');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recrea la tabla solo si no existe, para reversibilidad
        if (!Schema::hasTable('password_resets')) {
            Schema::create('password_resets', function (Blueprint $table) {
                $table->string('email')->index();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
        }
    }
};
