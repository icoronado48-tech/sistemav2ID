<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductoTerminadoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre_producto' => ['required', 'string', 'max:255', Rule::unique('producto_terminados')->ignore($this->producto_terminado)],
            'descripcion' => ['nullable', 'string', 'max:500'],
            'unidad_medida_salida' => ['required', 'string', 'max:50'],
            'stock_actual' => ['required', 'numeric', 'min:0'],
        ];
    }
}
