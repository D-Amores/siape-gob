<?php

namespace App\Http\Requests\Assigner;

use Illuminate\Foundation\Http\FormRequest;

class AssetsApiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Para hacer pruebas, en producción se debe ajustar.
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'option' => 'required|string|in:table,details',
        ];
    }
}
