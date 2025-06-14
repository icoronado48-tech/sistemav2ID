<?php

namespace App\Http\Requests\OrdenProduccion;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\OrdenProduccion; // Necesario para OrdenProduccionPolicy
use App\Models\User; // Asegúrate de que User también esté importado si lo usas en otros lugares de la política (aunque no en authorize)

class StoreOrdenProduccionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Autoriza la creación de una nueva orden de producción usando la OrdenProduccionPolicy.
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
        // para el método 'create', pasando la clase del modelo OrdenProduccion.
        return Auth::user()->can('create', OrdenProduccion::class);
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
            'estado' => ['required', 'string', 'in:pendiente,en_proceso,completada,cancelada'], // Estados iniciales o permitidos
            // 'creada_por_user_id' se asigna en el controlador
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
            'estado.required' => 'El estado de la orden de producción es obligatorio.',
            'estado.string' => 'El estado de la orden de producción debe ser una cadena de texto.',
            'estado.in' => 'El estado de la orden de producción no es válido.',
        ];
    }
}
