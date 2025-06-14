<?php

namespace App\Http\Requests\StockAlerta;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\StockAlerta; // Necesario para StockAlertaPolicy
use Illuminate\Validation\Rule;
use App\Models\User; // Asegúrate de que User también esté importado si lo usas en otros lugares de la política (aunque no en authorize)

class UpdateStockAlertaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Autoriza la actualización de una alerta de stock existente usando la StockAlertaPolicy.
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

        // 2. Si hay un usuario autenticado, delega la autorización a la política 'StockAlertaPolicy'
        // para el método 'update', pasando la instancia del modelo StockAlerta de la ruta.
        return Auth::user()->can('update', $this->route('stock_alerta'));
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Estas dos IDs pueden ser nulas pero no ambas al mismo tiempo si se permiten actualizaciones de entidad asociada.
            // Si las alertas se generan y la entidad asociada no cambia, estas reglas son menos críticas en un update.
            // 'materia_prima_id' => ['nullable', 'integer', 'exists:materias_primas,id', 'prohibits:producto_terminado_id'],
            // 'producto_terminado_id' => ['nullable', 'integer', 'exists:producto_terminados,id', 'prohibits:materia_prima_id'],
            'nivel_actual' => ['required', 'numeric', 'min:0'],
            'nivel_minimo' => ['required', 'numeric', 'min:0'],
            'tipo_alerta' => ['required', 'string', 'in:stock_bajo,agotado,lote_vencido,otro'], // Tipos de alerta permitidos
            'mensaje' => ['nullable', 'string', 'max:1000'],
            'resuelta' => ['required', 'boolean'], // Para marcar si la alerta ha sido resuelta
            // 'fecha_alerta' y 'generado_por_user_id' no deberían ser actualizables por el usuario.
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
            'nivel_actual.required' => 'El nivel actual es obligatorio.',
            'nivel_actual.numeric' => 'El nivel actual debe ser un número.',
            'nivel_actual.min' => 'El nivel actual no puede ser negativo.',
            'nivel_minimo.required' => 'El nivel mínimo es obligatorio.',
            'nivel_minimo.numeric' => 'El nivel mínimo debe ser un número.',
            'nivel_minimo.min' => 'El nivel mínimo no puede ser negativo.',
            'tipo_alerta.required' => 'El tipo de alerta es obligatorio.',
            'tipo_alerta.string' => 'El tipo de alerta debe ser una cadena de texto.',
            'tipo_alerta.in' => 'El tipo de alerta seleccionado no es válido.',
            'mensaje.string' => 'El mensaje debe ser una cadena de texto.',
            'mensaje.max' => 'El mensaje no debe exceder los :max caracteres.',
            'resuelta.required' => 'El estado "resuelta" es obligatorio.',
            'resuelta.boolean' => 'El estado "resuelta" debe ser verdadero o falso.',
        ];
    }
}
