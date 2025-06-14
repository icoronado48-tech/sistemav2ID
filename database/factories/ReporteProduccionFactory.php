<?php

namespace Database\Factories;

use App\Models\ReporteProduccion;
use App\Models\User;             // Importa User
use App\Models\OrdenProduccion;  // Importa OrdenProduccion
use App\Models\Role;             // Importa Role para filtrar usuarios
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class ReporteProduccionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ReporteProduccion::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $produccionUserIds = User::whereHas('role', function ($query) {
            $query->whereIn('nombre_rol', ['Jefe de Producción', 'administrador']);
        })->pluck('id')->toArray();
        $userId = $this->faker->randomElement($produccionUserIds) ?? null;

        $ordenProduccionId = OrdenProduccion::inRandomOrder()->first()->id ?? null;
        $orden = $ordenProduccionId ? OrdenProduccion::find($ordenProduccionId) : null;

        return [
            'user_id' => $userId,
            'orden_produccion_id' => $ordenProduccionId,
            'fecha_reporte' => $this->faker->dateTimeBetween('-3 months', 'now'),
            'detalles_produccion' => $this->faker->paragraph(2),
            'cantidad_producida_reportada' => $orden ? $this->faker->numberBetween($orden->cantidad_a_producir * 0.8, $orden->cantidad_a_producir * 1.1) : $this->faker->numberBetween(10, 500),
            'observaciones' => $this->faker->optional()->sentence(4),
        ];
    }
}
