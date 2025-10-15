<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class StorePersonnelAssetPendingRequest extends FormRequest
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
            "assignment_date" => "required|string|max:255",
            "confirmation_date" => "nullable|string|max:255",
            "asset_id" => "required|integer|exists:assets,id",
            "receiver_id" => "required|integer|exists:personnel,id",
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

            'asset_id.required' => 'El ID del activo es obligatorio.',
            'asset_id.integer' => 'El ID del activo debe ser un número entero.',
            'asset_id.exists' => 'El ID del activo proporcionado no existe.',

            'receiver_id.required' => 'El ID del receptor es obligatorio.',
            'receiver_id.integer' => 'El ID del receptor debe ser un número entero.',
            'receiver_id.exists' => 'El ID del receptor proporcionado no existe.',
        ];
    }

    public function attributes(): array
    {
        return [
            'assignment_date' => 'fecha de asignación',
            'confirmation_date' => 'fecha de confirmación',
            'asset_id' => 'ID del activo',
            'receiver_id' => 'ID del receptor',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('assignment_date')) {
            $this->merge([
                'assignment_date' => trim($this->input('assignment_date')),
            ]);
        }

        if ($this->has('confirmation_date')) {
            $this->merge([
                'confirmation_date' => trim($this->input('confirmation_date')),
            ]);
        }
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'errors' => $validator->errors()
        ], 422));
    }
}
