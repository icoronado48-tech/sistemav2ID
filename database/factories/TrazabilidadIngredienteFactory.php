<?php

namespace Database\Factories;

use App\Models\TrazabilidadIngrediente;
use App\Models\Lote;          // Importa el modelo Lote
use App\Models\MateriaPrima; // Importa el modelo MateriaPrima
use Illuminate\Database\Eloquent\Factories\Factory;

class TrazabilidadIngredienteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TrazabilidadIngrediente::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $loteId = Lote::inRandomOrder()->first()->id ?? null;
        $materiaPrimaId = MateriaPrima::inRandomOrder()->first()->id ?? null;

        return [
            'lote_id' => $loteId,
            'materia_prima_id' => $materiaPrimaId,
            'cantidad_utilizada' => $this->faker->randomFloat(2, 0.05, 50),
            'fecha_uso' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'observaciones' => $this->faker->optional()->sentence(3),
        ];
    }
}
