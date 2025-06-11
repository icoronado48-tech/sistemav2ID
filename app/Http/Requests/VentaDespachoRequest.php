<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VentaDespachoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cliente_id' => ['required', 'exists:clientes,id'],
            'fecha_venta_despacho' => ['required', 'date', 'before_or_equal:today'],
            'tipo_documento' => ['required', Rule::in(['Factura', 'Nota de Entrega', 'Pedido'])],
            'numero_documento' => ['nullable', 'string', 'max:255', Rule::unique('ventas_despachos')->ignore($this->venta_despacho)],
            'total_monto' => ['nullable', 'numeric', 'min:0'],
            'estado_despacho' => ['required', Rule::in(['Pendiente', 'Despachado Parcial', 'Despachado Completo', 'Cancelado'])],
            'registrado_por_user_id' => ['required', 'exists:users,id'],
            'observaciones' => ['nullable', 'string'],
        ];
    }
}
