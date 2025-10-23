<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdatePersonnelAssetRequest extends FormRequest
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
            'assignment_date' => 'sometimes|required|string|max:255',
            'confirmation_date' => 'sometimes|nullable|string|max:255',
            'path_acceptance_doc' => 'sometimes|nullable|string|max:255',
            'asset_id' => 'sometimes|required|integer|exists:assets,id',
            'assigner_id' => 'sometimes|required|integer|exists:personnel,id',
            'receiver_id' => 'sometimes|required|integer|exists:personnel,id',
        ];
    }

    public function messages(): array
    {
        return [
            'assignment_date.required' => 'La fecha de asignación es obligatoria.',
            'assignment_date.string' => 'La fecha de asignación debe ser una cadena de texto.',
            'assignment_date.max' => 'La fecha de asignación no debe exceder los 255 caracteres.',

            'confirmation_date.string' => 'La fecha de confirmación debe ser una cadena de texto.',
            'confirmation_date.max' => 'La fecha de confirmación no debe exceder los 255 caracteres.',

            'path_acceptance_doc.string' => 'El documento de aceptación PAC debe ser una cadena de texto.',
            'path_acceptance_doc.max' => 'El documento de aceptación PAC no debe exceder los 255 caracteres.',

            'asset_id.required' => 'El ID del activo es obligatorio.',
            'asset_id.integer' => 'El ID del activo debe ser un número entero.',
            'asset_id.exists' => 'El ID del activo proporcionado no existe.',

            'assigner_id.required' => 'El ID del asignador es obligatorio.',
            'assigner_id.integer' => 'El ID del asignador debe ser un número entero.',
            'assigner_id.exists' => 'El ID del asignador proporcionado no existe.',

            'receiver_id.required' => 'El ID del receptor es obligatorio.',
            'receiver_id.integer' => 'El ID del receptor debe ser un número entero.',
            'receiver_id.exists' => 'El ID del receptor proporcionado no existe.',
        ];
    }

    public function attributes (): array
    {
        return [
            'assignment_date' => 'fecha de asignación',
            'confirmation_date' => 'fecha de confirmación',
            'path_acceptance_doc' => 'documento de aceptación PAC',
            'asset_id' => 'ID del activo',
            'assigner_id' => 'ID del asignador',
            'receiver_id' => 'ID del receptor',
        ];
    }

    protected function prepareForValidation()
    {
        $fields = [
            'assignment_date',
            'confirmation_date',
            'path_acceptance_doc',
        ];

        foreach ($fields as $field) {
            if ($this->has($field)) {
                $this->merge([
                    $field => trim($this->input($field)),
                ]);
            }
        }
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Error de validación',
            'errors' => $validator->errors()
        ], 422));
    }

}
