<?php

namespace App\Http\Requests\Lote;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Lote; // Necesario para LotePolicy
use App\Models\User; // Asegúrate de que User también esté importado si lo usas en otros lugares de la política (aunque no en authorize)

class UpdateLoteQualityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Autoriza la actualización del estado de calidad de un lote usando la LotePolicy.
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

        // 2. Si hay un usuario autenticado, delega la autorización a la política 'LotePolicy'
        // para el método 'updateQualityStatus', pasando la instancia del modelo Lote de la ruta.
        return Auth::user()->can('updateQualityStatus', $this->route('lote'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'estado_calidad' => ['required', 'string', 'in:Pendiente,Aprobado,Rechazado,En Revisión'], // Estados de calidad permitidos
            'observaciones_calidad' => ['nullable', 'string', 'max:1000'],
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
            'estado_calidad.required' => 'El estado de calidad es obligatorio.',
            'estado_calidad.string' => 'El estado de calidad debe ser una cadena de texto.',
            'estado_calidad.in' => 'El estado de calidad proporcionado no es válido.',
            'observaciones_calidad.string' => 'Las observaciones de calidad deben ser una cadena de texto.',
            'observaciones_calidad.max' => 'Las observaciones de calidad no deben exceder los :max caracteres.',
        ];
    }
}
