<?php

namespace App\Http\Requests\RecepcionMateriaPrima;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\RecepcionMateriaPrima; // Necesario para RecepcionMateriaPrimaPolicy
use App\Models\User; // Asegúrate de que User también esté importado si lo usas en otros lugares de la política (aunque no en authorize)

class StoreRecepcionMateriaPrimaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Autoriza el registro de una recepción de materia prima usando la RecepcionMateriaPrimaPolicy.
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

        // 2. Si hay un usuario autenticado, delega la autorización a la política 'RecepcionMateriaPrimaPolicy'
        // para el método 'create', pasando la clase del modelo RecepcionMateriaPrima.
        return Auth::user()->can('create', RecepcionMateriaPrima::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'orden_compra_id' => ['required', 'integer', 'exists:orden_compra,id'],
            'materia_prima_id' => ['required', 'integer', 'exists:materias_primas,id'],
            'cantidad_recibida' => ['required', 'numeric', 'min:0.01'],
            'fecha_recepcion' => ['required', 'date'],
            'numero_lote_proveedor' => ['nullable', 'string', 'max:255'],
            // 'recibido_por_user_id' se asigna en el controlador
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
            'orden_compra_id.required' => 'La orden de compra es obligatoria.',
            'orden_compra_id.integer' => 'El ID de la orden de compra debe ser un número entero.',
            'orden_compra_id.exists' => 'La orden de compra seleccionada no es válida.',
            'materia_prima_id.required' => 'La materia prima es obligatoria.',
            'materia_prima_id.integer' => 'El ID de la materia prima debe ser un número entero.',
            'materia_prima_id.exists' => 'La materia prima seleccionada no es válida.',
            'cantidad_recibida.required' => 'La cantidad recibida es obligatoria.',
            'cantidad_recibida.numeric' => 'La cantidad recibida debe ser un número.',
            'cantidad_recibida.min' => 'La cantidad recibida debe ser al menos :min.',
            'fecha_recepcion.required' => 'La fecha de recepción es obligatoria.',
            'fecha_recepcion.date' => 'La fecha de recepción no tiene un formato válido.',
            'numero_lote_proveedor.string' => 'El número de lote del proveedor debe ser una cadena de texto.',
            'numero_lote_proveedor.max' => 'El número de lote del proveedor no debe exceder los :max caracteres.',
            'observaciones.string' => 'Las observaciones deben ser una cadena de texto.',
            'observaciones.max' => 'Las observaciones no deben exceder los :max caracteres.',
        ];
    }
}
