<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'orden_produccion_id' => ['required', 'exists:orden_produccion,id'],
            'producto_terminado_id' => ['required', 'exists:producto_terminados,id'],
            'cantidad_producida' => ['required', 'numeric', 'min:0.01'],
            'fecha_produccion' => ['required', 'date', 'before_or_equal:today'],
            'fecha_vencimiento' => ['nullable', 'date', 'after_or_equal:fecha_produccion'],
            'estado_calidad' => ['required', Rule::in(['Pendiente', 'Aprobado', 'Rechazado'])],
            'observaciones_calidad' => ['nullable', 'string'],
            'registrado_por_user_id' => ['required', 'exists:users,id'],
        ];
    }
}
