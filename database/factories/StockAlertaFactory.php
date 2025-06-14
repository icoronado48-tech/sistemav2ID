<?php

namespace Database\Factories;

use App\Models\StockAlerta;
use App\Models\MateriaPrima;    // Importa MateriaPrima
use App\Models\ProductoTerminado; // Importa ProductoTerminado
use App\Models\User;            // Importa User
use App\Models\Role;            // Importa Role para filtrar usuarios
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class StockAlertaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StockAlerta::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $materiaPrimaId = MateriaPrima::inRandomOrder()->first()->id ?? null;
        $productoTerminadoId = ProductoTerminado::inRandomOrder()->first()->id ?? null;
        $adminOrLogisticaUserIds = User::whereHas('role', function ($query) {
            $query->whereIn('nombre_rol', ['administrador', 'Supervisor de Logística', 'Usuario de Inventario']);
        })->pluck('id')->toArray();
        $generadaPorUserId = $this->faker->randomElement($adminOrLogisticaUserIds) ?? null;

        $tiposAlerta = ['bajo_stock', 'proximo_vencimiento', 'stock_negativo'];
        $estadosAlerta = ['activa', 'resuelta'];

        // Decide si la alerta es para MateriaPrima o ProductoTerminado
        $isMateriaPrima = $this->faker->boolean();
        $itemId = null;
        $itemModel = null;
        $nivelActual = 0;
        $umbral = 0;
        $mensaje = '';

        if ($isMateriaPrima && $materiaPrimaId) {
            $item = MateriaPrima::find($materiaPrimaId);
            $itemId = $item->id;
            $itemModel = MateriaPrima::class;
            $nivelActual = $this->faker->randomFloat(2, 0, $item->stock_minimo * 1.5);
            $umbral = $item->stock_minimo;
            $mensaje = 'Stock bajo de ' . $item->nombre_materia_prima;
        } elseif ($productoTerminadoId) {
            $item = ProductoTerminado::find($productoTerminadoId);
            $itemId = $item->id;
            $itemModel = ProductoTerminado::class;
            $nivelActual = $this->faker->numberBetween(0, $item->stock_minimo * 1.5);
            $umbral = $item->stock_minimo;
            $mensaje = 'Stock bajo de ' . $item->nombre_producto_terminado;
        }

        return [
            'tipo_alerta' => $this->faker->randomElement($tiposAlerta),
            'mensaje' => $mensaje,
            'nivel_actual' => $nivelActual,
            'umbral' => $umbral,
            'materia_prima_id' => ($itemModel == MateriaPrima::class) ? $itemId : null,
            'producto_terminado_id' => ($itemModel == ProductoTerminado::class) ? $itemId : null,
            'estado' => $this->faker->randomElement($estadosAlerta),
            'generada_por_user_id' => $generadaPorUserId,
            'fecha_alerta' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
