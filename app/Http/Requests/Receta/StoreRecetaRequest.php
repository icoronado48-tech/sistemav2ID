<?php

namespace App\Http\Requests\Receta;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Receta; // Necesario para RecetaPolicy
use App\Models\MateriaPrima; // Necesario para la validación de materia_prima_id en ingredientes
use App\Models\User; // Asegúrate de que User también esté importado si lo usas en otros lugares de la política (aunque no en authorize)

class StoreRecetaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Autoriza la creación de una nueva receta usando la RecetaPolicy.
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

        // 2. Si hay un usuario autenticado, delega la autorización a la política 'RecetaPolicy'
        // para el método 'create', pasando la clase del modelo Receta.
        return Auth::user()->can('create', Receta::class);
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
            'nombre_receta' => [
                'required',
                'string',
                'max:255',
                // La regla unique debe ser cuidadosa si quieres que el nombre sea único globalmente
                // o solo por producto terminado, o si no debe ser único en absoluto.
                // Rule::unique('recetas')->ignore($this->receta?->id), // Esto sería para 'update'
            ],
            'descripcion' => ['nullable', 'string', 'max:1000'],

            'ingredientes' => ['required', 'array', 'min:1'], // La receta debe tener al menos un ingrediente
            'ingredientes.*.materia_prima_id' => ['required', 'integer', 'exists:materia_primas,id'],
            'ingredientes.*.cantidad_necesaria' => ['required', 'numeric', 'min:0.01'],
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
            'producto_terminado_id.integer' => 'El producto terminado debe ser un número entero.',
            'producto_terminado_id.exists' => 'El producto terminado seleccionado no es válido.',

            'nombre_receta.required' => 'El nombre de la receta es obligatorio.',
            'nombre_receta.string' => 'El nombre de la receta debe ser una cadena de texto.',
            'nombre_receta.max' => 'El nombre de la receta no debe exceder los :max caracteres.',
            // 'nombre_receta.unique' => 'Ya existe una receta con este nombre.', // Habilitar si la regla unique está activa

            'descripcion.string' => 'La descripción debe ser una cadena de texto.',
            'descripcion.max' => 'La descripción no debe exceder los :max caracteres.',

            'ingredientes.required' => 'La receta debe contener al menos un ingrediente.',
            'ingredientes.array' => 'Los ingredientes de la receta deben ser un array.',
            'ingredientes.min' => 'La receta debe contener al menos :min ingrediente.',

            'ingredientes.*.materia_prima_id.required' => 'El ID de la materia prima en los detalles es obligatorio.',
            'ingredientes.*.materia_prima_id.integer' => 'El ID de la materia prima en los detalles debe ser un número entero.',
            'ingredientes.*.materia_prima_id.exists' => 'La materia prima seleccionada en un detalle no es válida.',

            'ingredientes.*.cantidad_necesaria.required' => 'La cantidad necesaria del ingrediente es obligatoria.',
            'ingredientes.*.cantidad_necesaria.numeric' => 'La cantidad necesaria del ingrediente debe ser un número.',
            'ingredientes.*.cantidad_necesaria.min' => 'La cantidad necesaria del ingrediente debe ser al menos :min.',
        ];
    }
}
