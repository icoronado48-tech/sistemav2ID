<?php

namespace App\Http\Requests\ReporteProduccion;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\ReporteProduccion; // Necesario para ReporteProduccionPolicy
use App\Models\User; // Asegúrate de que User también esté importado si lo usas en otros lugares de la política (aunque no en authorize)

class StoreReporteProduccionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Autoriza la creación de un reporte de producción usando la ReporteProduccionPolicy.
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

        // 2. Si hay un usuario autenticado, delega la autorización a la política 'ReporteProduccionPolicy'
        // para el método 'create', pasando la clase del modelo ReporteProduccion.
        return Auth::user()->can('create', ReporteProduccion::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // 'fecha_reporte' y 'generado_por_user_id' se asignan en el controlador
            'tipo_reporte' => ['required', 'string', 'in:diario,lote,semanal,mensual'], // Tipos de reporte permitidos
            'parametros_generacion' => ['nullable', 'array'], // Parámetros específicos para el tipo de reporte
            // Si el tipo es 'lote', requiere 'lote_id'
            'parametros_generacion.lote_id' => ['required_if:tipo_reporte,lote', 'integer', 'exists:lotes,id'],
            // Si el tipo es 'diario', requiere 'fecha'
            'parametros_generacion.fecha' => ['required_if:tipo_reporte,diario', 'date'],
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
            'tipo_reporte.required' => 'El tipo de reporte es obligatorio.',
            'tipo_reporte.string' => 'El tipo de reporte debe ser una cadena de texto.',
            'tipo_reporte.in' => 'El tipo de reporte seleccionado no es válido.',
            'parametros_generacion.array' => 'Los parámetros de generación deben ser un array.',
            'parametros_generacion.lote_id.required_if' => 'El ID del lote es obligatorio cuando el tipo de reporte es "lote".',
            'parametros_generacion.lote_id.integer' => 'El ID del lote debe ser un número entero.',
            'parametros_generacion.lote_id.exists' => 'El lote especificado para el reporte no existe.',
            'parametros_generacion.fecha.required_if' => 'La fecha es obligatoria cuando el tipo de reporte es "diario".',
            'parametros_generacion.fecha.date' => 'La fecha proporcionada no tiene un formato válido.',
            'observaciones.string' => 'Las observaciones deben ser una cadena de texto.',
            'observaciones.max' => 'Las observaciones no deben exceder los :max caracteres.',
        ];
    }
}
