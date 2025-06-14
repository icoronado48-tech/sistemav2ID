<?php

namespace Database\Factories;

use App\Models\Lote;
use App\Models\OrdenProduccion; // Importa el modelo OrdenProduccion
use App\Models\ProductoTerminado; // Importa el modelo ProductoTerminado
use App\Models\User;            // Importa el modelo User
use App\Models\Role;            // Importa el modelo Role para filtrar usuarios
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class LoteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Lote::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $ordenProduccionId = OrdenProduccion::inRandomOrder()->first()->id ?? null;
        $productoTerminadoId = ProductoTerminado::inRandomOrder()->first()->id ?? null;
        $userProduccionCalidadIds = User::whereHas('role', function ($query) {
            $query->whereIn('nombre_rol', ['Jefe de Producción', 'Operario de Producción', 'Supervisor de Calidad', 'administrador']);
        })->pluck('id')->toArray();
        $registradoPorUserId = $this->faker->randomElement($userProduccionCalidadIds) ?? null;
        $supervisadoPorUserId = $this->faker->randomElement($userProduccionCalidadIds) ?? null;

        $estadosCalidad = ['Pendiente', 'En Revisión', 'Aprobado', 'Rechazado'];
        $estadoCalidad = $this->faker->randomElement($estadosCalidad);
        $fechaProduccion = $this->faker->dateTimeBetween('-2 months', 'now');
        $fechaVencimiento = (clone $fechaProduccion)->addDays($this->faker->numberBetween(30, 365));

        return [
            'orden_produccion_id' => $ordenProduccionId,
            'producto_terminado_id' => $productoTerminadoId,
            'codigo_lote' => 'LT-' . strtoupper($this->faker->unique()->bothify('##??##')),
            'cantidad_producida' => $this->faker->numberBetween(50, 1000),
            'fecha_produccion' => $fechaProduccion,
            'fecha_vencimiento' => $fechaVencimiento,
            'estado_calidad' => $estadoCalidad,
            'registrado_por_user_id' => $registradoPorUserId,
            'supervisado_por_user_id' => ($estadoCalidad == 'Pendiente') ? null : $supervisadoPorUserId,
            'observaciones' => $this->faker->optional()->paragraph(1),
        ];
    }
}
