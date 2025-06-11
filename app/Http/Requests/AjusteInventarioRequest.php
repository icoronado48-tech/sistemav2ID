<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AjusteInventarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'materia_prima_id' => ['nullable', 'exists:materias_primas,id', 'required_without:producto_terminado_id'],
            'producto_terminado_id' => ['nullable', 'exists:producto_terminados,id', 'required_without:materia_prima_id'],
            'cantidad_ajustada' => ['required', 'numeric', 'nonzero'], // Puedes requerir que no sea 0 si es un ajuste
            'tipo_ajuste' => ['required', Rule::in(['Entrada', 'Salida', 'Correccion'])],
            'motivo' => ['nullable', 'string'],
            'fecha_ajuste' => ['required', 'date', 'before_or_equal:today'],
            'realizado_por_user_id' => ['required', 'exists:users,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'materia_prima_id.required_without' => 'Debe especificar una materia prima o un producto terminado para el ajuste.',
            'producto_terminado_id.required_without' => 'Debe especificar una materia prima o un producto terminado para el ajuste.',
            'cantidad_ajustada.nonzero' => 'La cantidad ajustada no puede ser cero.',
        ];
    }
}
