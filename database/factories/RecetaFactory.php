<?php

namespace Database\Factories;

use App\Models\Receta;
use App\Models\ProductoTerminado; // Importa el modelo ProductoTerminado
use Illuminate\Database\Eloquent\Factories\Factory;

class RecetaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Receta::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $productoTerminadoId = ProductoTerminado::inRandomOrder()->first()->id ?? null;

        return [
            'producto_terminado_id' => $productoTerminadoId,
            'nombre_receta' => 'Receta de ' . $this->faker->unique()->word,
            'descripcion' => $this->faker->paragraph(2),
        ];
    }
}
