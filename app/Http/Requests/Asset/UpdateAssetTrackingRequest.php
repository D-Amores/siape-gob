<?php

namespace App\Http\Requests\Asset;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class UpdateAssetTrackingRequest extends FormRequest
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
            //'maintenance_id' => 'required|exists:maintenances,id',
            //'work_done' => 'sometimes|string',
            //'observation' => 'sometimes|string',
            'status_id' => 'required|exists:statuses,id',
            'asset_status_id' => 'sometimes|exists:statuses,id',
            'comment' => 'required|string',

        ];
    }

        public function messages(): array
    {
        return [
            //'maintenance_id.required' => 'El campo mantenimiento es obligatorio.',
            //'maintenance_id.exists' => 'El mantenimiento seleccionado no existe.',
            //'work_done.string' => 'El campo trabajo realizado debe ser una cadena de texto.',
            //'observation.string' => 'El campo observación debe ser una cadena de texto.',
            'asset_status_id.exists' => 'El estado del activo seleccionado no existe.',
            'status_id.exists' => 'El estado seleccionado no existe.',
            'status_id.required' => 'El campo estado es obligatorio.',
            'comment.required' => 'El campo comentario es obligatorio.',
            'comment.string' => 'El campo comentario debe ser una cadena de texto.',
        ];
    }

    public function attributes(): array
    {
        return [
            //'maintenance_id' => 'mantenimiento',
            //'work_done' => 'trabajo realizado',
           //'observation' => 'observación',
            'status_id' => 'estado',
            'asset_status_id' => 'estado del activo',
            'comment' => 'comentario',
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
