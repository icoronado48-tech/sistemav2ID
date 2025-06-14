<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule; // Necesario para la regla 'unique' ignorando el ID actual
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Necesario para UserPolicy


class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Autoriza la actualización de un usuario existente usando la UserPolicy.
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

        // 2. Si hay un usuario autenticado, delega la autorización a la política 'UserPolicy'
        // para el método 'update', pasando la instancia del modelo User de la ruta.
        return Auth::user()->can('update', $this->route('user'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Recupera el ID del usuario de la ruta para ignorarlo en la validación 'unique' del email.
        $userId = $this->route('user')->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            // El email debe ser único, PERO ignorando el email del usuario actual que se está editando.
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($userId)],
            // La contraseña es opcional al actualizar. Si se proporciona, debe cumplir las reglas.
            'password' => ['nullable', 'string', 'confirmed', Password::defaults()],
            'role_id' => ['required', 'integer', 'exists:roles,id'],
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
            'password.string' => 'La contraseña debe ser una cadena de texto.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'role_id.required' => 'El rol del usuario es obligatorio.',
            'role_id.integer' => 'El ID del rol debe ser un número entero.',
            'role_id.exists' => 'El rol seleccionado no es válido.',
        ];
    }
}
