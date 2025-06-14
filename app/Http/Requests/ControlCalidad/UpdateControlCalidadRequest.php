<?php

namespace App\Http\Requests\ControlCalidad;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\ControlCalidad; // Necesario para ControlCalidadPolicy
use App\Models\User; // Asegúrate de que User también esté importado si lo usas en otros lugares de la política (aunque no en authorize)

class UpdateControlCalidadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Autoriza la actualización de un control de calidad existente usando la ControlCalidadPolicy.
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

        // 2. Si hay un usuario autenticado, delega la autorización a la política 'ControlCalidadPolicy'
        // para el método 'update', pasando la instancia del modelo ControlCalidad de la ruta.
        return Auth::user()->can('update', $this->route('control_calidad'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'lote_id' => ['required', 'integer', 'exists:lotes,id'], // El lote no debería cambiar
            // 'supervisado_por_user_id' y 'fecha_control' no deberían ser actualizables
            'resultado' => ['required', 'string', 'in:Pendiente,Aprobado,Rechazado,En Revisión'],
            'observaciones' => ['nullable', 'string', 'max:1000'],
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
            'lote_id.required' => 'El lote es obligatorio.',
            'lote_id.integer' => 'El ID del lote debe ser un número entero.',
            'lote_id.exists' => 'El lote seleccionado no es válido.',
            'resultado.required' => 'El resultado del control de calidad es obligatorio.',
            'resultado.string' => 'El resultado del control de calidad debe ser una cadena de texto.',
            'resultado.in' => 'El resultado del control de calidad no es válido.',
            'observaciones.string' => 'Las observaciones deben ser una cadena de texto.',
            'observaciones.max' => 'Las observaciones no deben exceder los :max caracteres.',
        ];
    }
}
