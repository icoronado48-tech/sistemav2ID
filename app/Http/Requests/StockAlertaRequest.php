<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StockAlertaRequest extends FormRequest
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
            'nivel_actual' => ['required', 'numeric', 'min:0'],
            'nivel_minimo' => ['required', 'numeric', 'min:0'],
            'tipo_alerta' => ['required', 'string', 'max:255'], // Considerar un Rule::in si los tipos son fijos
            'mensaje' => ['nullable', 'string'],
            'resuelta' => ['boolean'],
            'generado_por_user_id' => ['required', 'exists:users,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'materia_prima_id.required_without' => 'Debe especificar una materia prima o un producto terminado.',
            'producto_terminado_id.required_without' => 'Debe especificar una materia prima o un producto terminado.',
        ];
    }
}
