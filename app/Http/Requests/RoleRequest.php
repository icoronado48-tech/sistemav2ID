<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre_rol' => ['required', 'string', 'max:255', Rule::unique('roles')->ignore($this->role)],
            'descripcion' => ['nullable', 'string', 'max:500'],
        ];
    }
}
