<?php

namespace App\Http\Requests\ProductoTerminado;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductoTerminado; // Necesario para ProductoTerminadoPolicy
use App\Models\User; // Asegúrate de que User también esté importado si lo usas en otros lugares de la política (aunque no en authorize)

class StoreProductoTerminadoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Autoriza la creación de un nuevo producto terminado usando la ProductoTerminadoPolicy.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // 1. Primero, verifica si hay un usuario autenticado.
        // Si no hay un usuario autenticado, la solicitud no está autorizada.
        if (!Auth::check()) {
            return false;
        }

        // 2. Si hay un usuario autenticado, delega la autorización a la política 'ProductoTerminadoPolicy'
        // para el método 'create', pasando la clase del modelo ProductoTerminado.
        return Auth::user()->can('create', ProductoTerminado::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre_producto' => ['required', 'string', 'max:255', 'unique:producto_terminados,nombre_producto'],
            'descripcion' => ['nullable', 'string', 'max:1000'],
            'unidad_medida_salida' => ['required', 'string', 'max:50'],
            'stock_actual' => ['required', 'numeric', 'min:0'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nombre_producto.required' => 'El nombre del producto terminado es obligatorio.',
            'nombre_producto.string' => 'El nombre del producto terminado debe ser una cadena de texto.',
            'nombre_producto.max' => 'El nombre del producto terminado no debe exceder los :max caracteres.',
            'nombre_producto.unique' => 'Ya existe un producto terminado con este nombre.',
            'descripcion.string' => 'La descripción debe ser una cadena de texto.',
            'descripcion.max' => 'La descripción no debe exceder los :max caracteres.',
            'unidad_medida_salida.required' => 'La unidad de medida de salida es obligatoria.',
            'unidad_medida_salida.string' => 'La unidad de medida de salida debe ser una cadena de texto.',
            'unidad_medida_salida.max' => 'La unidad de medida de salida no debe exceder los :max caracteres.',
            'stock_actual.required' => 'El stock actual es obligatorio.',
            'stock_actual.numeric' => 'El stock actual debe ser un número.',
            'stock_actual.min' => 'El stock actual no puede ser negativo.',
        ];
    }
}
