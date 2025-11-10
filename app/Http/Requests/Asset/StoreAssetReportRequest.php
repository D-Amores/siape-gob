<?php

namespace App\Http\Requests\Asset;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class StoreAssetReportRequest extends FormRequest
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
            'asset_id' => 'required|exists:assets,id',
            'reported_by' => 'required|exists:personnel,id',
            //'status_id' => 'required|exists:statuses,id',
            'description' => 'required|string',
            'observation' => 'nullable|string',
            //'reported_at' => 'required|date',
            //'closed_at' => 'nullable|date|after_or_equal:reported_at',
        ];
    }

    public function messages(): array
    {
        return [
            'asset_id.required' => 'El campo activo es obligatorio.',
            'asset_id.exists' => 'El activo seleccionado no existe.',
            'reported_by.required' => 'El campo reportado por es obligatorio.',
            'reported_by.exists' => 'El personal seleccionado no existe.',
            //'status_id.required' => 'El campo estado es obligatorio.',
            //'status_id.exists' => 'El estado seleccionado no existe.',
            'description.required' => 'El campo descripción es obligatorio.',
            'description.string' => 'El campo descripción debe ser una cadena de texto.',
            'observation.string' => 'El campo observación debe ser una cadena de texto.',
            //'reported_at.required' => 'El campo fecha de reporte es obligatorio.',
            //'reported_at.date' => 'El campo fecha de reporte debe ser una fecha válida.',
            //'closed_at.date' => 'El campo fecha de cierre debe ser una fecha válida.',
            //'closed_at.after_or_equal' => 'El campo fecha de cierre debe ser una fecha posterior o igual a la fecha de reporte.',
        ];
    }

    public function attributes(): array
    {
        return [
            'asset_id' => 'activo',
            'reported_by' => 'reportado por',
            //'status_id' => 'estado',
            'description' => 'descripción',
            'observation' => 'observación',
            //'reported_at' => 'fecha de reporte',
            //'closed_at' => 'fecha de cierre',
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
