<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrdenCompraRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'proveedor_id' => ['required', 'exists:proveedores,id'],
            'creada_por_user_id' => ['required', 'exists:users,id'],
            'fecha_orden' => ['required', 'date', 'before_or_equal:today'],
            'fecha_entrega_estimada' => ['nullable', 'date', 'after_or_equal:fecha_orden'],
            'estado' => ['required', Rule::in(['Pendiente', 'Aprobada', 'Rechazada', 'Completada'])],
            'total_monto' => ['nullable', 'numeric', 'min:0'],
            'observaciones' => ['nullable', 'string'],
        ];
    }
}
