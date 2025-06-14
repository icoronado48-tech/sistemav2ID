<?php

namespace App\Http\Requests\VentaDespacho;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Models\VentaDespacho; // Necesario para VentaDespachoPolicy
use App\Models\Lote; // Necesario para la validación de lote_id en detalles
use App\Models\User; // Asegúrate de que User también esté importado si lo usas en otros lugares de la política (aunque no en authorize)

class UpdateVentaDespachoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Autoriza la actualización de una venta/despacho existente usando la VentaDespachoPolicy.
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

        // 2. Si hay un usuario autenticado, delega la autorización a la política 'VentaDespachoPolicy'
        // para el método 'update', pasando la instancia del modelo VentaDespacho de la ruta.
        return Auth::user()->can('update', $this->route('venta_despacho'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $ventaDespachoId = $this->route('venta_despacho')->id;

        return [
            'cliente_id' => ['required', 'integer', 'exists:clientes,id'],
            'fecha_venta_despacho' => ['required', 'date'],
            'tipo_documento' => ['required', 'string', 'in:Factura,Recibo,Nota de Entrega'],
            // El número de documento debe ser único, pero ignorando la venta actual.
            'numero_documento' => ['required', 'string', 'max:255', Rule::unique('ventas_despachos', 'numero_documento')->ignore($ventaDespachoId)],
            'estado_despacho' => ['required', 'string', 'in:Pendiente,Despachado,Cancelado'],
            'observaciones' => ['nullable', 'string', 'max:1000'],
            // 'registrado_por_user_id' y 'total_monto' se gestionan en el controlador

            // Reglas para los detalles de la venta/despacho (pueden ser opcionales en el update si no hay cambios)
            'detalles' => ['nullable', 'array'],
            'detalles.*.lote_id' => ['required', 'integer', 'exists:lotes,id'],
            'detalles.*.cantidad_vendida_despachada' => ['required', 'numeric', 'min:0.01'],
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
            'cliente_id.required' => 'El cliente es obligatorio.',
            'cliente_id.integer' => 'El ID del cliente debe ser un número entero.',
            'cliente_id.exists' => 'El cliente seleccionado no es válido.',
            'fecha_venta_despacho.required' => 'La fecha de venta/despacho es obligatoria.',
            'fecha_venta_despacho.date' => 'La fecha de venta/despacho no tiene un formato válido.',
            'tipo_documento.required' => 'El tipo de documento es obligatorio.',
            'tipo_documento.string' => 'El tipo de documento debe ser una cadena de texto.',
            'tipo_documento.in' => 'El tipo de documento no es válido.',
            'numero_documento.required' => 'El número de documento es obligatorio.',
            'numero_documento.string' => 'El número de documento debe ser una cadena de texto.',
            'numero_documento.max' => 'El número de documento no debe exceder los :max caracteres.',
            'numero_documento.unique' => 'Ya existe una venta/despacho con este número de documento.',
            'estado_despacho.required' => 'El estado de despacho es obligatorio.',
            'estado_despacho.string' => 'El estado de despacho debe ser una cadena de texto.',
            'estado_despacho.in' => 'El estado de despacho no es válido.',
            'observaciones.string' => 'Las observaciones deben ser una cadena de texto.',
            'observaciones.max' => 'Las observaciones no deben exceder los :max caracteres.',

            'detalles.array' => 'Los detalles de la venta deben ser un array.',
            'detalles.*.lote_id.required' => 'El ID del lote en los detalles es obligatorio.',
            'detalles.*.lote_id.integer' => 'El ID del lote en los detalles debe ser un número entero.',
            'detalles.*.lote_id.exists' => 'El lote seleccionado en un detalle no es válido.',
            'detalles.*.cantidad_vendida_despachada.required' => 'La cantidad vendida/despachada en los detalles es obligatoria.',
            'detalles.*.cantidad_vendida_despachada.numeric' => 'La cantidad vendida/despachada en los detalles debe ser un número.',
            'detalles.*.cantidad_vendida_despachada.min' => 'La cantidad vendida/despachada en los detalles debe ser al menos :min.',
            'detalles.*.precio_unitario.required' => 'El precio unitario en los detalles es obligatorio.',
            'detalles.*.precio_unitario.numeric' => 'El precio unitario en los detalles debe ser un número.',
            'detalles.*.precio_unitario.min' => 'El precio unitario en los detalles debe ser al menos :min.',
        ];
    }
}
