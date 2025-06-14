<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password; // Para reglas de contraseña más complejas

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * La autorización para registrar un nuevo usuario podría ser `true` por defecto,
     * o `false` si solo los administradores pueden crear usuarios (en cuyo caso usarías una política o middleware).
     * Dado que el AuthController tiene `showRegistrationForm` y `register`, asumimos que el registro está abierto.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true; // Permitir que cualquier usuario (incluso no autenticado) pueda registrarse.
    }

    /**
     * Get the validation rules that apply to the request.
     * Define las reglas de validación para los campos de registro.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],               // Nombre es obligatorio, string, max 255
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'], // Email obligatorio, string, formato email, max 255, único en la tabla 'users'
            'password' => ['required', 'string', 'confirmed', Password::defaults()], // Contraseña obligatoria, string, debe ser confirmada (password_confirmation), y cumplir con las reglas por defecto (min 8, mayúscula, etc.)
            // 'role_id' => ['required', 'exists:roles,id'], // Si el rol se selecciona en el registro y no se asigna por defecto en el controlador
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
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no debe exceder los :max caracteres.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.string' => 'El correo electrónico debe ser una cadena de texto.',
            'email.email' => 'El formato del correo electrónico no es válido.',
            'email.max' => 'El correo electrónico no debe exceder los :max caracteres.',
            'email.unique' => 'Ya existe un usuario con este correo electrónico.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.string' => 'La contraseña debe ser una cadena de texto.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            // Mensajes para Password::defaults() se gestionan automáticamente por Laravel
        ];
    }
}
