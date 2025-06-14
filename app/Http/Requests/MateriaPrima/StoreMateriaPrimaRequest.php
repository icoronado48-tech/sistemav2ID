<?php

namespace App\Http\Requests\MateriaPrima;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\MateriaPrima; // Necesario para MateriaPrimaPolicy
use App\Models\User; // Asegúrate de que User también esté importado si lo usas en otros lugares de la política (aunque no en authorize)

class StoreMateriaPrimaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Autoriza la creación de una nueva materia prima usando la MateriaPrimaPolicy.
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

        // 2. Si hay un usuario autenticado, delega la autorización a la política 'MateriaPrimaPolicy'
        // para el método 'create', pasando la clase del modelo MateriaPrima.
        return Auth::user()->can('create', MateriaPrima::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255', 'unique:materias_primas,nombre'],
            'unidad_medida' => ['required', 'string', 'max:50'],
            'stock_actual' => ['required', 'numeric', 'min:0'],
            'stock_minimo' => ['required', 'numeric', 'min:0'],
            'proveedor_id' => ['nullable', 'integer', 'exists:proveedores,id'], // Opcional, pero debe existir si se proporciona
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
            'nombre.required' => 'El nombre de la materia prima es obligatorio.',
            'nombre.string' => 'El nombre de la materia prima debe ser una cadena de texto.',
            'nombre.max' => 'El nombre de la materia prima no debe exceder los :max caracteres.',
            'nombre.unique' => 'Ya existe una materia prima con este nombre.',
            'unidad_medida.required' => 'La unidad de medida es obligatoria.',
            'unidad_medida.string' => 'La unidad de medida debe ser una cadena de texto.',
            'unidad_medida.max' => 'La unidad de medida no debe exceder los :max caracteres.',
            'stock_actual.required' => 'El stock actual es obligatorio.',
            'stock_actual.numeric' => 'El stock actual debe ser un número.',
            'stock_actual.min' => 'El stock actual no puede ser negativo.',
            'stock_minimo.required' => 'El stock mínimo es obligatorio.',
            'stock_minimo.numeric' => 'El stock mínimo debe ser un número.',
            'stock_minimo.min' => 'El stock mínimo no puede ser negativo.',
            'proveedor_id.integer' => 'El ID del proveedor debe ser un número entero.',
            'proveedor_id.exists' => 'El proveedor seleccionado no es válido.',
        ];
    }
}
