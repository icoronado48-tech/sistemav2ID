<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecepcionMateriaPrimaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'orden_compra_id' => ['required', 'exists:orden_compra,id'],
            'materia_prima_id' => ['required', 'exists:materias_primas,id'],
            'cantidad_recibida' => ['required', 'numeric', 'min:0.01'],
            'fecha_recepcion' => ['required', 'date', 'before_or_equal:today'],
            'numero_lote_proveedor' => ['nullable', 'string', 'max:255'],
            'recibido_por_user_id' => ['required', 'exists:users,id'],
        ];
    }
}
