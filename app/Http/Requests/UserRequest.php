<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Implementa lógica de autorización aquí (e.g., Gate::allows('manage-users'))
        return true; // Por simplicidad, permite a todos los autenticados.
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->user)], // 'user' es el parámetro de ruta para el modelo User
            'password' => ['nullable', 'string', 'min:8', 'confirmed'], // 'confirmed' busca 'password_confirmation'
            'role_id' => ['required', 'exists:roles,id'], // Asegura que el role_id exista en la tabla roles
        ];

        // Si se está creando un usuario, la contraseña es requerida
        if ($this->isMethod('post')) {
            $rules['password'] = ['required', 'string', 'min:8', 'confirmed'];
        }

        return $rules;
    }

    // Opcional: Personalizar mensajes de error
    public function messages(): array
    {
        return [
            'email.unique' => 'El correo electrónico ya ha sido registrado.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'role_id.exists' => 'El rol seleccionado no es válido.',
        ];
    }
}
