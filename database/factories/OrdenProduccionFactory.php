<?php

namespace Database\Factories;

use App\Models\OrdenProduccion;
use App\Models\ProductoTerminado; // Importa el modelo ProductoTerminado
use App\Models\User;            // Importa el modelo User
use App\Models\Role;            // Importa el modelo Role para filtrar usuarios
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class OrdenProduccionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrdenProduccion::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $productoTerminadoId = ProductoTerminado::inRandomOrder()->first()->id ?? null;
        $produccionUserIds = User::whereHas('role', function ($query) {
            $query->whereIn('nombre_rol', ['Jefe de Producción', 'Operario de Producción', 'administrador']);
        })->pluck('id')->toArray();
        $registradoPorUserId = $this->faker->randomElement($produccionUserIds) ?? null;

        $estados = ['pendiente', 'en_proceso', 'completada', 'cancelada'];
        $estado = $this->faker->randomElement($estados);
        $fechaInicio = $this->faker->dateTimeBetween('-3 months', 'now');
        $fechaFin = null;
        if ($estado == 'completada') {
            $fechaFin = $this->faker->dateTimeBetween($fechaInicio, 'now');
        } elseif ($estado == 'cancelada') {
            $fechaFin = $this->faker->dateTimeBetween($fechaInicio, 'now');
        }

        return [
            'producto_terminado_id' => $productoTerminadoId,
            'cantidad_a_producir' => $this->faker->numberBetween(10, 500),
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'estado' => $estado,
            'progreso' => ($estado == 'completada') ? 100 : (($estado == 'en_proceso') ? $this->faker->numberBetween(10, 90) : 0),
            'observaciones' => $this->faker->optional()->paragraph(1),
            'registrado_por_user_id' => $registradoPorUserId,
        ];
    }
}
