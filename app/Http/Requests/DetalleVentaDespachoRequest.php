<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DetalleVentaDespachoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'venta_despacho_id' => ['required', 'exists:ventas_despachos,id'],
            'lote_id' => ['required', 'exists:lotes,id'],
            'cantidad_vendida_despachada' => ['required', 'numeric', 'min:0.01'],
            'precio_unitario' => ['required', 'numeric', 'min:0'],
        ];
    }
}
