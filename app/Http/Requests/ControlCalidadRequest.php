<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ControlCalidadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'lote_id' => ['required', 'exists:lotes,id'],
            'supervisado_por_user_id' => ['required', 'exists:users,id'],
            'fecha_control' => ['required', 'date', 'before_or_equal:today'],
            'resultado' => ['required', Rule::in(['Aprobado', 'Rechazado'])],
            'observaciones' => ['nullable', 'string'],
        ];
    }
}
