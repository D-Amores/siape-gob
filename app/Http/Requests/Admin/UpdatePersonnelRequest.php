<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Services\Tools;

class UpdatePersonnelRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>| string>
     */
    public function rules(): array
    {   //Solo para patch
        return [
            'name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'sometimes|required|email|unique:personnel,email,' . $this->getPersonnelId(),
            'area_id' => 'sometimes|required|exists:areas,id',
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
            'area_id.required' => 'El área es obligatoria.',
            'area_id.exists' => 'El área seleccionada no es válida.',
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
            'area_id' => 'área',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge(array_filter([
            'name' => $this->has('name') ? trim($this->input('name')) : null,
            'last_name' => $this->has('last_name') ? trim($this->input('last_name')) : null,
            'middle_name' => $this->has('middle_name') ? trim($this->input('middle_name')) : null,
            'phone' => $this->has('phone') ? Tools::formatPhoneNumber($this->input('phone')) : null,
            'email' => $this->has('email') ? trim(strtolower($this->input('email'))) : null,
        ]));
    }

    protected function failedValidation(Validator $validator)
    {
        $response = response()->json([
            'ok' => false,
            'message' => 'Error de validación.',
            'errors' => $validator->errors()
        ], 422);

        throw new HttpResponseException($response);
    }

    private function getPersonnelId(): int|string|null
    {
        $personnel = $this->route('personnel');
        return is_object($personnel) ? $personnel->getKey() : $personnel;
    }
}
