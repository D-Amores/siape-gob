<?php

namespace App\Http\Requests\Asset;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class StoreAssetTrackingRequest extends FormRequest
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
            'maintenance_report_id' => 'required|exists:maintenance_reports,id',
            //'perfomed_by' => 'required|exists:personnel,id',
        ];
    }

    public function messages(): array
    {
        return [
            'maintenance_report_id.required' => 'El campo informe de mantenimiento es obligatorio.',
            'maintenance_report_id.exists' => 'El informe de mantenimiento seleccionado no existe.',
            //'performed_by.required' => 'El campo realizado por es obligatorio.',
            //'performed_by.exists' => 'El personal seleccionado no existe.',
        ];
    }

    public function attributes(): array
    {
        return [
            'maintenance_report_id' => 'informe de mantenimiento',
            //'performed_by' => 'realizado por',
        ];
    }

    protected function prepareForValidation(): void
    {
        //
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
