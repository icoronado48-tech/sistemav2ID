<?php

namespace App\Http\Requests\OrdenCompra;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\OrdenCompra; // Necesario para OrdenCompraPolicy
use App\Models\MateriaPrima; // Necesario para la validación de materia_prima_id en detalles
use App\Models\User; // Asegúrate de que User también esté importado si lo usas en otros lugares de la política (aunque no en authorize)

class UpdateOrdenCompraRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Autoriza la actualización de una orden de compra existente usando la OrdenCompraPolicy.
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

        // 2. Si hay un usuario autenticado, delega la autorización a la política 'OrdenCompraPolicy'
        // para el método 'update', pasando la instancia del modelo OrdenCompra de la ruta.
        return Auth::user()->can('update', $this->route('orden_compra'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'proveedor_id' => ['required', 'integer', 'exists:proveedores,id'],
            'fecha_orden' => ['required', 'date'],
            'fecha_entrega_estimada' => ['nullable', 'date', 'after_or_equal:fecha_orden'],
            'estado' => ['required', 'string', 'in:Pendiente,Aprobada,Rechazada,Completada'],
            'observaciones' => ['nullable', 'string', 'max:1000'],
            // Reglas para los detalles de la orden de compra (similares a Store, pero pueden permitir vacíos si no hay detalles)
            'detalles' => ['nullable', 'array'], // Puede no enviar detalles si no hay cambios o se eliminan todos
            'detalles.*.materia_prima_id' => ['required', 'integer', 'exists:materias_primas,id'],
            'detalles.*.cantidad' => ['required', 'numeric', 'min:0.01'],
            'detalles.*.precio_unitario' => ['required', 'numeric', 'min:0.01'],
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
            'proveedor_id.required' => 'El proveedor es obligatorio.',
            'proveedor_id.integer' => 'El ID del proveedor debe ser un número entero.',
            'proveedor_id.exists' => 'El proveedor seleccionado no es válido.',
            'fecha_orden.required' => 'La fecha de la orden es obligatoria.',
            'fecha_orden.date' => 'La fecha de la orden no tiene un formato válido.',
            'fecha_entrega_estimada.date' => 'La fecha de entrega estimada no tiene un formato válido.',
            'fecha_entrega_estimada.after_or_equal' => 'La fecha de entrega estimada no puede ser anterior a la fecha de la orden.',
            'estado.required' => 'El estado de la orden es obligatorio.',
            'estado.string' => 'El estado de la orden debe ser una cadena de texto.',
            'estado.in' => 'El estado de la orden no es válido.',
            'observaciones.string' => 'Las observaciones deben ser una cadena de texto.',
            'observaciones.max' => 'Las observaciones no deben exceder los :max caracteres.',

            'detalles.array' => 'Los detalles de la orden deben ser un array.',
            'detalles.*.materia_prima_id.required' => 'El ID de la materia prima en los detalles es obligatorio.',
            'detalles.*.materia_prima_id.integer' => 'El ID de la materia prima en los detalles debe ser un número entero.',
            'detalles.*.materia_prima_id.exists' => 'La materia prima seleccionada en un detalle no es válida.',
            'detalles.*.cantidad.required' => 'La cantidad en los detalles es obligatoria.',
            'detalles.*.cantidad.numeric' => 'La cantidad en los detalles debe ser un número.',
            'detalles.*.cantidad.min' => 'La cantidad en los detalles debe ser al menos :min.',
            'detalles.*.precio_unitario.required' => 'El precio unitario en los detalles es obligatorio.',
            'detalles.*.precio_unitario.numeric' => 'El precio unitario en los detalles debe ser un número.',
            'detalles.*.precio_unitario.min' => 'El precio unitario en los detalles debe ser al menos :min.',
        ];
    }
}
