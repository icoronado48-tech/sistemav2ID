<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * La autorización para login es siempre true, ya que cualquier usuario (incluso no autenticado)
     * puede intentar iniciar sesión. La lógica de autenticación ocurre en el controlador.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * Define las reglas de validación para los campos de email y contraseña.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255'], // Email es obligatorio, string, formato email, max 255 caracteres
            'password' => ['required', 'string'],                   // Contraseña es obligatoria, string
            'remember' => ['nullable', 'boolean'],                  // 'Recuérdame' es opcional, debe ser un booleano
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     * Personaliza los mensajes de error para una mejor experiencia de usuario.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.string' => 'El correo electrónico debe ser una cadena de texto.',
            'email.email' => 'El formato del correo electrónico no es válido.',
            'email.max' => 'El correo electrónico no debe exceder los :max caracteres.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.string' => 'La contraseña debe ser una cadena de texto.',
            'remember.boolean' => 'El campo "Recuérdame" debe ser verdadero o falso.',
        ];
    }
}
