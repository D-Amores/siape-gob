<?php

namespace App\Http\Requests\Asset;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class CloseAssetTrackingRequest extends FormRequest
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
            //'status_id' => 'required|exists:statuses,id',
            'asset_status_id' => 'required|exists:statuses,id',
            'observation' => 'required|string',
            'work_done' => 'required|string',
            //'comment' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'asset_status_id.required' => 'El campo estado del activo es obligatorio.',
            'asset_status_id.exists' => 'El estado del activo seleccionado no existe.',
            //'status_id.required' => 'El campo estado es obligatorio.',
            //'status_id.exists' => 'El estado seleccionado no existe.',
            'observation.required' => 'El campo observación es obligatorio.',
            'observation.string' => 'El campo observación debe ser una cadena de texto.',
            'work_done.required' => 'El campo trabajo realizado es obligatorio.',
            'work_done.string' => 'El campo trabajo realizado debe ser una cadena de texto.',
            //'comment.string' => 'El campo comentario debe ser una cadena de texto.',
        ];
    }

    public function attributes(): array
    {
        return [
            'asset_status_id' => 'estado del activo',
            //'status_id' => 'estado',
            'observation' => 'observación',
            'work_done' => 'trabajo realizado',
            //'comment' => 'comentario',
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
