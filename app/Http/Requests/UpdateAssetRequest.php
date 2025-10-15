<?php

namespace App\Http\Requests;

use App\Models\Asset;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Log;

class UpdateAssetRequest extends FormRequest
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
            'inventory_number' => "sometimes|string|max:255|unique:assets,inventory_number," . $this->getAssetInventoryNumber(),
            'model' => 'sometimes|string|max:100',
            'serial_number' => "sometimes|nullable|string|max:255|unique:assets,serial_number," . $this->getAssetInventoryNumber(),
            'cpu' => 'sometimes|string|max:100',
            'speed' => 'sometimes|string|max:100',
            'memory' => 'sometimes|string|max:100',
            'storage' => 'sometimes|string|max:100',
            'description' => 'sometimes|nullable|string',
            'brand_id' => 'sometimes|exists:brands,id',
            'category_id' => 'sometimes|exists:categories,id',
            'is_active' => 'sometimes|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'inventory_number.required' => 'El número de inventario es obligatorio',
            'inventory_number.unique' => 'Este número de inventario ya existe',
            'inventory_number.max' => 'El número de inventario no debe exceder 100 caracteres',

            'model.required' => 'El modelo es obligatorio',
            'model.max' => 'El modelo no debe exceder 100 caracteres',

            'serial_number.unique' => 'Este número de serie ya existe',
            'serial_number.max' => 'El número de serie no debe exceder 100 caracteres',

            'cpu.max' => 'El procesador no debe exceder 100 caracteres',
            'speed.max' => 'La velocidad no debe exceder 100 caracteres',
            'memory.max' => 'La memoria no debe exceder 100 caracteres',
            'storage.max' => 'El almacenamiento no debe exceder 100 caracteres',

            'description.string' => 'La descripción debe ser texto válido',

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
        $data = [];

        if ($this->has('inventory_number')) {
            $data['inventory_number'] = strtoupper(trim($this->inventory_number));
        }

        if ($this->has('model')) {
            $data['model'] = trim($this->model);
        }

        if ($this->has('serial_number')) {
            $data['serial_number'] = $this->serial_number ? strtoupper(trim($this->serial_number)) : null;
        }

        if ($this->has('cpu')) {
            $data['cpu'] = trim($this->cpu);
        }

        if ($this->has('speed')) {
            $data['speed'] = trim($this->speed);
        }

        if ($this->has('memory')) {
            $data['memory'] = trim($this->memory);
        }

        if ($this->has('storage')) {
            $data['storage'] = trim($this->storage);
        }

        if ($this->has('description')) {
            $data['description'] = trim($this->description ?? '');
        }

        if ($this->has('is_active')) {
            $data['is_active'] = filter_var($this->is_active, FILTER_VALIDATE_BOOLEAN);
        }

        $this->merge($data);
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'ok' => false,
            'message' => 'Error de validación',
            'errors' => $validator->errors()
        ], 422));
    }

    private function getAssetInventoryNumber(): int|string|null
    {
        $asset = $this->route('asset');
        return is_object($asset) ? $asset->getKey() : $asset;
    }

}
