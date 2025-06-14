<?php

namespace Database\Factories;

use App\Models\VentaDespacho;
use App\Models\Cliente; // Importa Cliente
use App\Models\User;    // Importa User
use App\Models\Role;    // Importa Role para filtrar usuarios
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class VentaDespachoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = VentaDespacho::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $clienteId = Cliente::inRandomOrder()->first()->id ?? null;
        $userVentasIds = User::whereHas('role', function ($query) {
            $query->whereIn('nombre_rol', ['Gerente de Ventas', 'administrador']);
        })->pluck('id')->toArray();
        $registradoPorUserId = $this->faker->randomElement($userVentasIds) ?? null;

        $tiposDocumento = ['Factura', 'Nota de Entrega'];
        $estadosDespacho = ['pendiente', 'en_despacho', 'despachado', 'cancelado'];
        $estado = $this->faker->randomElement($estadosDespacho);
        $fechaVenta = $this->faker->dateTimeBetween('-4 months', 'now');

        return [
            'cliente_id' => $clienteId,
            'fecha_venta_despacho' => $fechaVenta,
            'tipo_documento' => $this->faker->randomElement($tiposDocumento),
            'numero_documento' => 'VD-' . strtoupper($this->faker->unique()->bothify('###??#')),
            'estado_despacho' => $estado,
            'registrado_por_user_id' => $registradoPorUserId,
            'observaciones' => $this->faker->optional()->paragraph(1),
        ];
    }
}
