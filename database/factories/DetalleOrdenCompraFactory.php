<?php

namespace Database\Factories;

use App\Models\DetalleOrdenCompra;
use App\Models\OrdenCompra;  // Importa OrdenCompra
use App\Models\MateriaPrima; // Importa MateriaPrima
use Illuminate\Database\Eloquent\Factories\Factory;

class DetalleOrdenCompraFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DetalleOrdenCompra::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $ordenCompraId = OrdenCompra::inRandomOrder()->first()->id ?? null;
        $materiaPrimaId = MateriaPrima::inRandomOrder()->first()->id ?? null;

        $cantidad = $this->faker->numberBetween(10, 500);
        $precio = $this->faker->randomFloat(2, 1, 30);

        return [
            'orden_compra_id' => $ordenCompraId,
            'materia_prima_id' => $materiaPrimaId,
            'cantidad_pedida' => $cantidad,
            'precio_unitario' => $precio,
            'subtotal' => $cantidad * $precio, // Calcula el subtotal
        ];
    }
}
