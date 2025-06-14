<?php

namespace Database\Factories;

use App\Models\OrdenCompra;
use App\Models\Proveedor; // Importa Proveedor
use App\Models\User;     // Importa User
use App\Models\Role;     // Importa Role para filtrar usuarios
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class OrdenCompraFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrdenCompra::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $proveedorId = Proveedor::inRandomOrder()->first()->id ?? null;
        $userLogisticaIds = User::whereHas('role', function ($query) {
            $query->whereIn('nombre_rol', ['Supervisor de Logística', 'administrador']);
        })->pluck('id')->toArray();
        $registradoPorUserId = $this->faker->randomElement($userLogisticaIds) ?? null;

        $estados = ['pendiente', 'enviada', 'recibida_parcial', 'recibida_completa', 'cancelada'];
        $estado = $this->faker->randomElement($estados);
        $fechaPedido = $this->faker->dateTimeBetween('-6 months', 'now');
        $fechaEstimadaEntrega = (clone $fechaPedido)->addDays($this->faker->numberBetween(7, 60));
        $fechaRecepcionReal = null;
        if (in_array($estado, ['recibida_parcial', 'recibida_completa'])) {
            $fechaRecepcionReal = $this->faker->dateTimeBetween($fechaPedido, 'now');
        }

        return [
            'proveedor_id' => $proveedorId,
            'registrado_por_user_id' => $registradoPorUserId,
            'numero_documento' => 'OC-' . strtoupper($this->faker->unique()->bothify('##??##??')),
            'fecha_pedido' => $fechaPedido,
            'fecha_estimada_entrega' => $fechaEstimadaEntrega,
            'fecha_recepcion_real' => $fechaRecepcionReal,
            'estado' => $estado,
            'observaciones' => $this->faker->optional()->paragraph(1),
        ];
    }
}
