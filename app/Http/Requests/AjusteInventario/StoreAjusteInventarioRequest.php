<?php

namespace App\Http\Requests\AjusteInventario;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\AjusteInventario; // Necesario para AjusteInventarioPolicy
use Illuminate\Validation\Rule; // Necesario para la regla 'required_without_all'

class StoreAjusteInventarioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Autoriza la creación de un ajuste de inventario usando la AjusteInventarioPolicy.
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

        // 2. Si hay un usuario autenticado, delega la autorización a la política 'AjusteInventarioPolicy'
        // para el método 'create', pasando la clase del modelo AjusteInventario.
        return Auth::user()->can('create', AjusteInventario::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Una de estas dos IDs es obligatoria, pero no ambas.
            'materia_prima_id' => [
                'nullable',
                'integer',
                'exists:materias_primas,id',
                Rule::requiredIf(empty($this->producto_terminado_id)), // Requerido si producto_terminado_id está vacío
                'prohibits:producto_terminado_id', // Prohíbe la presencia de producto_terminado_id
            ],
            'producto_terminado_id' => [
                'nullable',
                'integer',
                'exists:producto_terminados,id',
                Rule::requiredIf(empty($this->materia_prima_id)), // Requerido si materia_prima_id está vacío
                'prohibits:materia_prima_id', // Prohíbe la presencia de materia_prima_id
            ],
            'cantidad_ajustada' => ['required', 'numeric', 'nonzero'], // Puede ser positivo (entrada) o negativo (salida/merma)
            'tipo_ajuste' => ['required', 'string', 'in:Entrada,Salida,Merma,Reconciliación'], // Tipos de ajuste permitidos
            'motivo' => ['required', 'string', 'max:500'],
            'fecha_ajuste' => ['required', 'date'],
            // 'realizado_por_user_id' se asigna en el controlador
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
            'materia_prima_id.required' => 'Debe seleccionar una materia prima o un producto terminado.',
            'materia_prima_id.integer' => 'El ID de la materia prima debe ser un número entero.',
            'materia_prima_id.exists' => 'La materia prima seleccionada no es válida.',
            'materia_prima_id.prohibits' => 'No puede especificar una materia prima y un producto terminado al mismo tiempo.',
            'producto_terminado_id.required' => 'Debe seleccionar una materia prima o un producto terminado.',
            'producto_terminado_id.integer' => 'El ID del producto terminado debe ser un número entero.',
            'producto_terminado_id.exists' => 'El producto terminado seleccionado no es válido.',
            'producto_terminado_id.prohibits' => 'No puede especificar una materia prima y un producto terminado al mismo tiempo.',
            'cantidad_ajustada.required' => 'La cantidad ajustada es obligatoria.',
            'cantidad_ajustada.numeric' => 'La cantidad ajustada debe ser un número.',
            'cantidad_ajustada.nonzero' => 'La cantidad ajustada no puede ser cero.',
            'tipo_ajuste.required' => 'El tipo de ajuste es obligatorio.',
            'tipo_ajuste.string' => 'El tipo de ajuste debe ser una cadena de texto.',
            'tipo_ajuste.in' => 'El tipo de ajuste seleccionado no es válido.',
            'motivo.required' => 'El motivo del ajuste es obligatorio.',
            'motivo.string' => 'El motivo debe ser una cadena de texto.',
            'motivo.max' => 'El motivo no debe exceder los :max caracteres.',
            'fecha_ajuste.required' => 'La fecha del ajuste es obligatoria.',
            'fecha_ajuste.date' => 'La fecha del ajuste no tiene un formato válido.',
        ];
    }
}
