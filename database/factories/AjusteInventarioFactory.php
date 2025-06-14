<?php

namespace Database\Factories;

use App\Models\AjusteInventario;
use App\Models\MateriaPrima;    // Importa MateriaPrima
use App\Models\ProductoTerminado; // Importa ProductoTerminado
use App\Models\User;             // Importa User
use App\Models\Role;             // Importa Role para filtrar usuarios
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class AjusteInventarioFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AjusteInventario::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $materiaPrimaId = MateriaPrima::inRandomOrder()->first()->id ?? null;
        $productoTerminadoId = ProductoTerminado::inRandomOrder()->first()->id ?? null;
        $userInventarioIds = User::whereHas('role', function ($query) {
            $query->whereIn('nombre_rol', ['Usuario de Inventario', 'Supervisor de Logística', 'administrador']);
        })->pluck('id')->toArray();
        $userId = $this->faker->randomElement($userInventarioIds) ?? null;

        $tiposAjuste = ['entrada', 'salida', 'correccion'];
        $tipoAjuste = $this->faker->randomElement($tiposAjuste);
        $cantidad = $this->faker->numberBetween(1, 100);
        $cantidadAjustada = ($tipoAjuste == 'salida') ? -$cantidad : $cantidad;

        // Decide si el ajuste es para MateriaPrima o ProductoTerminado
        $isMateriaPrima = $this->faker->boolean();
        $mpId = null;
        $ptId = null;

        if ($isMateriaPrima && $materiaPrimaId) {
            $mpId = $materiaPrimaId;
        } elseif ($productoTerminadoId) {
            $ptId = $productoTerminadoId;
        }


        return [
            'materia_prima_id' => $mpId,
            'producto_terminado_id' => $ptId,
            'user_id' => $userId,
            'fecha_ajuste' => $this->faker->dateTimeBetween('-2 months', 'now'),
            'tipo_ajuste' => $tipoAjuste,
            'cantidad_ajustada' => $cantidadAjustada,
            'razon' => $this->faker->sentence(5),
        ];
    }
}
