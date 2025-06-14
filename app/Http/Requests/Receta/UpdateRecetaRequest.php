<?php

namespace App\Http\Requests\Receta;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Models\Receta; // Necesario para RecetaPolicy
use App\Models\MateriaPrima; // Necesario para la validación de materia_prima_id en ingredientes
use App\Models\User; // Asegúrate de que User también esté importado si lo usas en otros lugares de la política (aunque no en authorize)

class UpdateRecetaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Autoriza la actualización de una receta existente usando la RecetaPolicy.
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
        // para el método 'update', pasando la instancia del modelo Receta de la ruta.
        // Laravel automáticamente inyectará la instancia de Receta basada en el parámetro de ruta.
        return Auth::user()->can('update', $this->route('receta'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Obtener el ID de la receta que se está actualizando para ignorarla en la regla unique
        $recetaId = $this->route('receta')->id;

        return [
            'producto_terminado_id' => ['required', 'integer', 'exists:producto_terminados,id'],
            'nombre_receta' => [
                'required',
                'string',
                'max:255',
                // Asegura que el nombre sea único, excepto para la receta que se está editando.
                Rule::unique('recetas')->ignore($recetaId),
            ],
            'descripcion' => ['nullable', 'string', 'max:1000'],

            // Los ingredientes pueden no ser obligatorios para la actualización si es un array vacío permitido,
            // pero si se envían, deben seguir las reglas.
            // Si quieres forzar al menos un ingrediente en la actualización, cambia 'nullable' a 'required'.
            'ingredientes' => ['array', 'nullable'],
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
            'nombre_receta.unique' => 'Ya existe una receta con este nombre.',

            'descripcion.string' => 'La descripción debe ser una cadena de texto.',
            'descripcion.max' => 'La descripción no debe exceder los :max caracteres.',

            'ingredientes.array' => 'Los ingredientes de la receta deben ser un array.',
            // 'ingredientes.min' => 'La receta debe contener al menos :min ingrediente.', // Descomentar si se hace obligatorio

            'ingredientes.*.materia_prima_id.required' => 'El ID de la materia prima en los detalles es obligatorio.',
            'ingredientes.*.materia_prima_id.integer' => 'El ID de la materia prima en los detalles debe ser un número entero.',
            'ingredientes.*.materia_prima_id.exists' => 'La materia prima seleccionada en un detalle no es válida.',

            'ingredientes.*.cantidad_necesaria.required' => 'La cantidad necesaria del ingrediente es obligatoria.',
            'ingredientes.*.cantidad_necesaria.numeric' => 'La cantidad necesaria del ingrediente debe ser un número.',
            'ingredientes.*.cantidad_necesaria.min' => 'La cantidad necesaria del ingrediente debe ser al menos :min.',
        ];
    }
}
