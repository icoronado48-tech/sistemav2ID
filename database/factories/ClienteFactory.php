<?php

namespace Database\Factories;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClienteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Cliente::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nombre_cliente' => $this->faker->unique()->company,
            'contacto_nombre' => $this->faker->name,
            'contacto_email' => $this->faker->unique()->safeEmail,
            'contacto_telefono' => $this->faker->phoneNumber,
            'direccion' => $this->faker->address,
            'ciudad' => $this->faker->city,
            'pais' => $this->faker->country,
        ];
    }
}
