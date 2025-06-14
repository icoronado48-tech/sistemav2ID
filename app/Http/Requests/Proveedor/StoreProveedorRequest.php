<?php

namespace App\Http\Requests\Proveedor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Proveedor; // Necesario para ProveedorPolicy
use App\Models\User; // Asegúrate de que User también esté importado si lo usas en otros lugares de la política (aunque no en authorize)

class StoreProveedorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Autoriza la creación de un nuevo proveedor usando la ProveedorPolicy.
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

        // 2. Si hay un usuario autenticado, delega la autorización a la política 'ProveedorPolicy'
        // para el método 'create', pasando la clase del modelo Proveedor.
        return Auth::user()->can('create', Proveedor::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre_proveedor' => ['required', 'string', 'max:255', 'unique:proveedores,nombre_proveedor'],
            'contacto' => ['nullable', 'string', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:proveedores,email'],
            'direccion' => ['nullable', 'string', 'max:500'],
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
            'nombre_proveedor.required' => 'El nombre del proveedor es obligatorio.',
            'nombre_proveedor.string' => 'El nombre del proveedor debe ser una cadena de texto.',
            'nombre_proveedor.max' => 'El nombre del proveedor no debe exceder los :max caracteres.',
            'nombre_proveedor.unique' => 'Ya existe un proveedor con este nombre.',
            'contacto.string' => 'El contacto debe ser una cadena de texto.',
            'contacto.max' => 'El contacto no debe exceder los :max caracteres.',
            'telefono.string' => 'El teléfono debe ser una cadena de texto.',
            'telefono.max' => 'El teléfono no debe exceder los :max caracteres.',
            'email.string' => 'El correo electrónico debe ser una cadena de texto.',
            'email.email' => 'El formato del correo electrónico del proveedor no es válido.',
            'email.max' => 'El correo electrónico del proveedor no debe exceder los :max caracteres.',
            'email.unique' => 'Ya existe un proveedor con este correo electrónico.',
            'direccion.string' => 'La dirección debe ser una cadena de texto.',
            'direccion.max' => 'La dirección no debe exceder los :max caracteres.',
        ];
    }
}
