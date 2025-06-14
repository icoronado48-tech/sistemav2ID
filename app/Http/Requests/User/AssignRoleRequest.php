<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Necesario para UserPolicy

class AssignRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Autoriza la asignación de un rol a un usuario.
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
        // para el método 'assignRole', pasando la instancia del usuario a la que se le asigna el rol.
        // Asume que el parámetro de ruta es 'user'.
        // return Auth::user()->can('assignRole', $this->route('user')); // Descomentar esta línea y comentar la siguiente si tienes una política 'assignRole'

        // Si no tienes una habilidad 'assignRole' específica y esto es una forma de 'update' general,
        // podrías usar 'update'. Sin embargo, es más preciso tener una habilidad dedicada.
        // Por ahora, lo dejo como un 'create' genérico en User::class para evitar un nuevo error,
        // pero lo ideal sería lo comentado arriba con una habilidad 'assignRole' en la política.
        return Auth::user()->can('create', User::class); // Mantengo este por si no hay política específica aún
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'role_id' => ['required', 'integer', 'exists:roles,id'], // El rol es obligatorio y debe existir
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
            'role_id.required' => 'El ID del rol es obligatorio.',
            'role_id.integer' => 'El ID del rol debe ser un número entero.',
            'role_id.exists' => 'El rol seleccionado no es válido.',
        ];
    }
}
