<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class StoreAssetRequest extends FormRequest
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

    public const SOMETIMES_STRING_MAX_100 = 'sometimes|string|max:100';
    public function rules(): array
    {
        return [
            'inventory_number' => 'string|required|unique:assets|max:255',
            'model' => self::SOMETIMES_STRING_MAX_100,
            'serial_number' => 'nullable|unique:assets|max:255',
            'cpu' => self::SOMETIMES_STRING_MAX_100,
            'speed' => self::SOMETIMES_STRING_MAX_100,
            'memory' => self::SOMETIMES_STRING_MAX_100,
            'storage' => self::SOMETIMES_STRING_MAX_100,
            'description' => 'nullable|string',
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'is_active' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            // inventory_number
            'inventory_number.required' => 'El número de inventario es obligatorio',
            'inventory_number.unique' => 'Este número de inventario ya existe',
            'inventory_number.max' => 'El número de inventario no debe exceder 255 caracteres',

            // model
            'model.required' => 'El modelo es obligatorio',
            'model.max' => 'El modelo no debe exceder 100 caracteres',

            // serial_number
            'serial_number.unique' => 'Este número de serie ya existe',
            'serial_number.max' => 'El número de serie no debe exceder 255 caracteres',

            // cpu, speed, memory, storage
            'cpu.max' => 'El procesador no debe exceder 100 caracteres',
            'speed.max' => 'La velocidad no debe exceder 100 caracteres',
            'memory.max' => 'La memoria no debe exceder 100 caracteres',
            'storage.max' => 'El almacenamiento no debe exceder 100 caracteres',

            // description
            'description.string' => 'La descripción debe ser texto válido',

            // relaciones
            'brand_id.required' => 'La marca es obligatoria',
            'brand_id.exists' => 'La marca seleccionada no existe',
            'category_id.required' => 'La categoría es obligatoria',
            'category_id.exists' => 'La categoría seleccionada no existe',
        ];
    }

    public function attributes(): array
    {
        return [
            'inventory_number' => 'número de inventario',
            'model' => 'modelo',
            'serial_number' => 'número de serie',
            'cpu' => 'procesador',
            'speed' => 'velocidad',
            'memory' => 'memoria',
            'storage' => 'almacenamiento',
            'description' => 'descripción',
            'brand_id' => 'marca',
            'category_id' => 'categoría',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'inventory_number' => strtoupper(trim($this->inventory_number)),
            'model' => trim($this->model),
            'serial_number' => $this->serial_number ? strtoupper(trim($this->serial_number)) : null,
            'cpu' => trim($this->cpu),
            'speed' => trim($this->speed),
            'memory' => trim($this->memory),
            'storage' => trim($this->storage),
            'description' => trim($this->description ?? ''),
            'is_active' => $this->has('is_active') ? (bool) $this->is_active : true,
        ]);
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'ok' => false,
            'message' => 'Error en los datos del activo.',
            'errors' => $validator->errors()
        ], 422));
    }
}
