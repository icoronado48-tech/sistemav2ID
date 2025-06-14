<?php

namespace App\Http\Requests\OrdenProduccion;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\OrdenProduccion; // Necesario para OrdenProduccionPolicy
use App\Models\User; // Asegúrate de que User también esté importado si lo usas en otros lugares de la política (aunque no en authorize)

class UpdateOrdenProduccionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Autoriza la actualización de una orden de producción existente usando la OrdenProduccionPolicy.
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
        // para el método 'update', pasando la instancia del modelo OrdenProduccion de la ruta.
        return Auth::user()->can('update', $this->route('orden_produccion'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'producto_terminado_id' => ['required', 'integer', 'exists:producto_terminados,id'],
            'cantidad_a_producir' => ['required', 'numeric', 'min:0.01'],
            'fecha_planificada_inicio' => ['required', 'date'],
            'fecha_planificada_fin' => ['required', 'date', 'after_or_equal:fecha_planificada_inicio'],
            // El estado no se actualiza a través de este request general, sino con updateStatus.
            // 'estado' => ['required', 'string', 'in:pendiente,en_proceso,completada,cancelada'],
            'fecha_real_inicio' => ['nullable', 'date', 'before_or_equal:fecha_real_fin'],
            'fecha_real_fin' => ['nullable', 'date', 'after_or_equal:fecha_real_inicio'],
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
            'producto_terminado_id.required' => 'El producto terminado es obligatorio.',
            'producto_terminado_id.integer' => 'El ID del producto terminado debe ser un número entero.',
            'producto_terminado_id.exists' => 'El producto terminado seleccionado no es válido.',
            'cantidad_a_producir.required' => 'La cantidad a producir es obligatoria.',
            'cantidad_a_producir.numeric' => 'La cantidad a producir debe ser un número.',
            'cantidad_a_producir.min' => 'La cantidad a producir debe ser al menos :min.',
            'fecha_planificada_inicio.required' => 'La fecha planificada de inicio es obligatoria.',
            'fecha_planificada_inicio.date' => 'La fecha planificada de inicio no tiene un formato válido.',
            'fecha_planificada_fin.required' => 'La fecha planificada de fin es obligatoria.',
            'fecha_planificada_fin.date' => 'La fecha planificada de fin no tiene un formato válido.',
            'fecha_planificada_fin.after_or_equal' => 'La fecha planificada de fin no puede ser anterior a la fecha de inicio.',
            'fecha_real_inicio.date' => 'La fecha real de inicio no tiene un formato válido.',
            'fecha_real_inicio.before_or_equal' => 'La fecha real de inicio no puede ser posterior a la fecha real de fin.',
            'fecha_real_fin.date' => 'La fecha real de fin no tiene un formato válido.',
            'fecha_real_fin.after_or_equal' => 'La fecha real de fin no puede ser anterior a la fecha real de inicio.',
        ];
    }
}
