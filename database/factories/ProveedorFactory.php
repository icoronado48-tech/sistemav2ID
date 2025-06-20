<?php

namespace Database\Factories;

use App\Models\Proveedor;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProveedorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Proveedor::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nombre_proveedor' => $this->faker->unique()->company,
            'contacto_nombre' => $this->faker->name,
            'contacto_email' => $this->faker->unique()->safeEmail,
            'contacto_telefono' => $this->faker->phoneNumber,
            'direccion' => $this->faker->address,
            'ciudad' => $this->faker->city,
            'pais' => $this->faker->country,
        ];
    }
}
