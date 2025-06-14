<?php

namespace Database\Factories;

use App\Models\RecetaIngrediente;
use App\Models\Receta;         // Importa el modelo Receta
use App\Models\MateriaPrima;  // Importa el modelo MateriaPrima
use Illuminate\Database\Eloquent\Factories\Factory;

class RecetaIngredienteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RecetaIngrediente::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $recetaId = Receta::inRandomOrder()->first()->id ?? null;
        $materiaPrimaId = MateriaPrima::inRandomOrder()->first()->id ?? null;

        return [
            'receta_id' => $recetaId,
            'materia_prima_id' => $materiaPrimaId,
            'cantidad_necesaria' => $this->faker->randomFloat(2, 0.1, 10),
        ];
    }
}
