<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReporteProduccionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fecha_reporte' => ['required', 'date', 'before_or_equal:today'],
            'tipo_reporte' => ['required', 'string', 'max:255'],
            'contenido_reporte' => ['required', 'string'], // Dependerá de cómo almacenes el contenido (JSON, texto, etc.)
            'generado_por_user_id' => ['required', 'exists:users,id'],
        ];
    }
}
