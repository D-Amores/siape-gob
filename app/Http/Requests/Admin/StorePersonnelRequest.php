<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Services\Tools;

class StorePersonnelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'required|email|unique:personnel,email',
            'curp' => 'required|string|unique:personnel,curp',
            'area_id' => 'required',
            'area_name' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'last_name.required' => 'El apellido es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser una dirección de correo electrónico válida.',
            'email.unique' => 'El correo electrónico ya ha sido registrado.',
            'curp.required' => 'El CURP es obligatorio.',
            'curp.unique' => 'El personal con este CURP ya ha sido registrado.',
            'area_id.required' => 'El área es obligatoria.',
            'area_id.exists' => 'El área seleccionada no es válida.',
            'area_name.required' => 'El nombre del área es obligatorio.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nombre',
            'last_name' => 'apellido',
            'middle_name' => 'segundo apellido',
            'phone' => 'teléfono',
            'email' => 'correo electrónico',
            'curp' => 'CURP',
            'area_id' => 'área',
            'area_name' => 'nombre del área',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => strtolower(trim($this->email)),
            'name' => trim($this->name),
            'last_name' => trim($this->last_name),
            'middle_name' => trim($this->middle_name ?? ''),
            'phone' => Tools::formatPhoneNumber(trim($this->phone ?? '')),
            'curp' => trim($this->curp ?? ''),
            'area_name' => trim($this->area_name ?? ''),
        ]);
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'ok' => false,
            'message' => 'Error de validación.',
            'errors' => $validator->errors()
        ], 422));
    }    
}
