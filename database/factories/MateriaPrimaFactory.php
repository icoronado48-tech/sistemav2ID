<?php

namespace Database\Factories;

use App\Models\MateriaPrima;
use App\Models\Proveedor; // Importa el modelo Proveedor
use Illuminate\Database\Eloquent\Factories\Factory;

class MateriaPrimaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MateriaPrima::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $proveedorId = Proveedor::inRandomOrder()->first()->id ?? null;
        $unidadesMedida = ['kg', 'litros', 'gramos', 'unidades', 'ml'];

        return [
            'nombre_materia_prima' => $this->faker->unique()->word . ' ' . $this->faker->randomElement(['harina', 'azúcar', 'esencia', 'colorante', 'cacao']),
            'descripcion' => $this->faker->sentence(5),
            'unidad_medida' => $this->faker->randomElement($unidadesMedida),
            'stock_actual' => $this->faker->randomFloat(2, 10, 1000),
            'stock_minimo' => $this->faker->randomFloat(2, 1, 50),
            'costo_unitario' => $this->faker->randomFloat(2, 0.5, 20),
            'proveedor_id' => $proveedorId,
        ];
    }
}
