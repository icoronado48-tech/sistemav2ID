<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RecetaIngredienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'receta_id' => ['required', 'exists:recetas,id'],
            'materia_prima_id' => ['required', 'exists:materias_primas,id'],
            'cantidad_necesaria' => ['required', 'numeric', 'min:0.01'],
            // Asegura que no se duplique un ingrediente para la misma receta
            Rule::unique('receta_ingredientes')->where(function ($query) {
                return $query->where('receta_id', $this->receta_id)
                    ->where('materia_prima_id', $this->materia_prima_id);
            })->ignore($this->receta_ingrediente),
        ];
    }
}
