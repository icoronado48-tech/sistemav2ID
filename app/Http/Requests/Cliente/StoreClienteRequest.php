<?php

namespace App\Http\Requests\Cliente;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Cliente; // Necesario para ClientePolicy
use App\Models\User; // Asegúrate de que User también esté importado si lo usas en otros lugares de la política (aunque no en authorize)

class StoreClienteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Autoriza la creación de un nuevo cliente usando la ClientePolicy.
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

        // 2. Si hay un usuario autenticado, delega la autorización a la política 'ClientePolicy'
        // para el método 'create', pasando la clase del modelo Cliente.
        return Auth::user()->can('create', Cliente::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre_cliente' => ['required', 'string', 'max:255'],
            'cedula_rif' => ['required', 'string', 'max:20', 'unique:clientes,cedula_rif'], // Cédula/RIF obligatorio y único
            'telefono' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:clientes,email'],
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
            'nombre_cliente.required' => 'El nombre del cliente es obligatorio.',
            'nombre_cliente.string' => 'El nombre del cliente debe ser una cadena de texto.',
            'nombre_cliente.max' => 'El nombre del cliente no debe exceder los :max caracteres.',
            'cedula_rif.required' => 'La cédula/RIF es obligatoria.',
            'cedula_rif.string' => 'La cédula/RIF debe ser una cadena de texto.',
            'cedula_rif.max' => 'La cédula/RIF no debe exceder los :max caracteres.',
            'cedula_rif.unique' => 'Ya existe un cliente con esta cédula/RIF.',
            'telefono.string' => 'El teléfono debe ser una cadena de texto.',
            'telefono.max' => 'El teléfono no debe exceder los :max caracteres.',
            'email.string' => 'El correo electrónico debe ser una cadena de texto.',
            'email.email' => 'El formato del correo electrónico del cliente no es válido.',
            'email.max' => 'El correo electrónico del cliente no debe exceder los :max caracteres.',
            'email.unique' => 'Ya existe un cliente con este correo electrónico.',
            'direccion.string' => 'La dirección debe ser una cadena de texto.',
            'direccion.max' => 'La dirección no debe exceder los :max caracteres.',
        ];
    }
}
