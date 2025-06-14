<?php

namespace App\Http\Requests\OrdenProduccion;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\OrdenProduccion; // Necesario para OrdenProduccionPolicy
use App\Models\User; // Asegúrate de que User también esté importado si lo usas en otros lugares de la política (aunque no en authorize)

class UpdateOrdenProduccionStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Autoriza la actualización del estado de una orden de producción usando la OrdenProduccionPolicy.
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

        // 2. Si hay un usuario autenticado, delega la autorización a la política 'OrdenProduccionPolicy'
        // para el método 'updateStatus', pasando la instancia del modelo OrdenProduccion de la ruta.
        return Auth::user()->can('updateStatus', $this->route('orden_produccion'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'estado' => ['required', 'string', 'in:pendiente,en_proceso,completada,cancelada'], // Nuevos estados permitidos
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
            'estado.required' => 'El nuevo estado de la orden de producción es obligatorio.',
            'estado.string' => 'El estado debe ser una cadena de texto.',
            'estado.in' => 'El estado proporcionado no es válido.',
        ];
    }
}
