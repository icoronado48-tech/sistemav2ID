<?php

namespace Database\Factories;

use App\Models\ControlCalidad;
use App\Models\Lote;  // Importa el modelo Lote
use App\Models\User; // Importa el modelo User
use App\Models\Role; // Importa el modelo Role para filtrar usuarios
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class ControlCalidadFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ControlCalidad::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $loteId = Lote::inRandomOrder()->first()->id ?? null;
        $calidadUserIds = User::whereHas('role', function ($query) {
            $query->whereIn('nombre_rol', ['Supervisor de Calidad', 'administrador']);
        })->pluck('id')->toArray();
        $supervisadoPorUserId = $this->faker->randomElement($calidadUserIds) ?? null;

        $resultados = ['Aprobado', 'Rechazado'];
        $estadosProceso = ['Completo', 'Pendiente de Revisión'];

        return [
            'lote_id' => $loteId,
            'fecha_control' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'resultado' => $this->faker->randomElement($resultados),
            'observaciones' => $this->faker->optional()->paragraph(1),
            'supervisado_por_user_id' => $supervisadoPorUserId,
            'estado_proceso' => $this->faker->randomElement($estadosProceso),
        ];
    }
}
