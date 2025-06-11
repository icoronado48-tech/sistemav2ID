<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrdenProduccionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'producto_terminado_id' => ['required', 'exists:producto_terminados,id'],
            'cantidad_a_producir' => ['required', 'numeric', 'min:0.01'],
            'fecha_planificada_inicio' => ['required', 'date', 'after_or_equal:today'],
            'fecha_planificada_fin' => ['required', 'date', 'after_or_equal:fecha_planificada_inicio'],
            'fecha_real_inicio' => ['nullable', 'date', 'before_or_equal:today'],
            'fecha_real_fin' => ['nullable', 'date', 'after_or_equal:fecha_real_inicio'],
            'estado' => ['required', Rule::in(['pendiente', 'en_proceso', 'completada', 'cancelada'])],
            'creada_por_user_id' => ['required', 'exists:users,id'],
        ];
    }
}
