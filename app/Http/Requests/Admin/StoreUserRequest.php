<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class StoreUserRequest extends FormRequest
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
            'username' => 'required|string|max:20|min:6|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'personnel_id' => 'required|exists:personnel,id|unique:users,personnel_id',
            'role_id' => 'required|exists:roles,id',
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'El nombre de usuario es obligatorio.',
            'username.unique' => 'Usa otro nombre de usuario.',
            'password.required' => 'La contraseña es obligatoria.',
            'personnel_id.required' => 'El ID de personal es obligatorio.',
            'personnel_id.exists' => 'El ID de personal no existe en la base de datos.',
            'username.max' => 'El nombre de usuario no debe exceder los 20 caracteres.',
            'username.min' => 'El nombre de usuario debe tener al menos 6 caracteres.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'personnel_id.unique' => 'El personal ya tiene una cuenta de usuario asociada.',
            'role_id.required' => 'El rol es obligatorio.',
            'role_id.exists' => 'El rol seleccionado no existe en la base de datos.',
        ];
    }

    public function attributes(): array
    {
        return [
            'username' => 'nombre de usuario',
            'password' => 'contraseña',
            'personnel_id' => 'ID de personal',
            'role_id' => 'ID de rol',
        ];
    }

    // public function prepareForValidation(): void
    // {
    //     $this->merge([
    //         'username' => strtolower($this->username),
    //     ]);
    // }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'ok' => false,
            'message' => 'Error de validación.',
            'errors' => $validator->errors()
        ], 422));
    }    
}
