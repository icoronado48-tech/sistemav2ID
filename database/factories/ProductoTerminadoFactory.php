<?php

namespace Database\Factories;

use App\Models\ProductoTerminado;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductoTerminadoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductoTerminado::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $unidadesMedidaProducto = ['unidades', 'cajas', 'paquetes', 'bolsas'];

        return [
            'nombre_producto_terminado' => $this->faker->unique()->word . ' ' . $this->faker->randomElement(['bizcocho', 'galletas', 'pastel', 'pan artesanal']),
            'descripcion' => $this->faker->sentence(6),
            'unidad_medida' => $this->faker->randomElement($unidadesMedidaProducto),
            'precio_venta' => $this->faker->randomFloat(2, 5, 50),
            'stock_actual' => $this->faker->numberBetween(0, 500),
            'stock_minimo' => $this->faker->numberBetween(10, 50),
        ];
    }
}
