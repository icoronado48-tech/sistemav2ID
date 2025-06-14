<?php

namespace Database\Factories;

use App\Models\RecepcionMateriaPrima;
use App\Models\OrdenCompra;  // Importa OrdenCompra
use App\Models\MateriaPrima; // Importa MateriaPrima
use App\Models\User;         // Importa User
use App\Models\Role;         // Importa Role para filtrar usuarios
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class RecepcionMateriaPrimaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RecepcionMateriaPrima::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $ordenCompraId = OrdenCompra::inRandomOrder()->first()->id ?? null;
        $materiaPrimaId = MateriaPrima::inRandomOrder()->first()->id ?? null;
        $userLogisticaIds = User::whereHas('role', function ($query) {
            $query->whereIn('nombre_rol', ['Supervisor de Logística', 'Usuario de Inventario', 'administrador']);
        })->pluck('id')->toArray();
        $recibidoPorUserId = $this->faker->randomElement($userLogisticaIds) ?? null;

        $estadosRecepcion = ['completa', 'parcial', 'rechazada'];

        return [
            'orden_compra_id' => $ordenCompraId,
            'materia_prima_id' => $materiaPrimaId,
            'cantidad_recibida' => $this->faker->numberBetween(5, 500),
            'fecha_recepcion' => $this->faker->dateTimeBetween('-3 months', 'now'),
            'estado_recepcion' => $this->faker->randomElement($estadosRecepcion),
            'recibido_por_user_id' => $recibidoPorUserId,
            'observaciones' => $this->faker->optional()->paragraph(1),
        ];
    }
}
