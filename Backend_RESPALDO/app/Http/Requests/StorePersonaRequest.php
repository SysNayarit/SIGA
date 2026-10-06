<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePersonaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // La autorización real (Policies) se aplicará en el controlador.
        // Por ahora permitimos que pase a la validación de datos.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'nombres' => ['required', 'string', 'max:150'],
            'apellido_paterno' => ['nullable', 'string', 'max:100'],
            'apellido_materno' => ['nullable', 'string', 'max:100'],
            'curp' => ['nullable', 'string', 'size:18', 'unique:institutional.persons,curp'],
            'rfc' => ['nullable', 'string', 'size:13', 'unique:institutional.persons,rfc'],
            'fecha_nacimiento' => ['nullable', 'date', 'before_or_equal:today'],
            'sexo' => ['nullable', 'string', Rule::in(['MASCULINO', 'FEMENINO'])],
            'estado_civil' => ['nullable', 'string', Rule::in(['SOLTERO', 'CASADO'])],
            'correo_institucional' => ['nullable', 'email', 'max:254', 'unique:institutional.persons,correo_institucional'],
            'correo_personal' => ['nullable', 'email', 'max:254'],
            'id_pais_origen' => ['nullable', 'uuid', 'exists:institutional.countries,id_pais'],
            'id_pais_nacimiento' => ['nullable', 'uuid', 'exists:institutional.countries,id_pais'],
            'id_territorio_nacimiento' => ['nullable', 'uuid', 'exists:institutional.territories,id_territorio'],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Normalización básica antes de validar
        if ($this->has('curp')) {
            $this->merge(['curp' => strtoupper(trim($this->curp))]);
        }
        if ($this->has('rfc')) {
            $this->merge(['rfc' => strtoupper(trim($this->rfc))]);
        }
        if ($this->has('correo_institucional')) {
            $this->merge(['correo_institucional' => strtolower(trim($this->correo_institucional))]);
        }
        if ($this->has('correo_personal')) {
            $this->merge(['correo_personal' => strtolower(trim($this->correo_personal))]);
        }
    }
}