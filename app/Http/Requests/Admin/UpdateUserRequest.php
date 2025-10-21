<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Services\Tools;

class UpdateUserRequest extends FormRequest
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
            'username' => 'sometimes|string|min:6|max:255|unique:users,username,' . $this->route('user')->id,
            'password' => 'sometimes|string|min:8|confirmed',
            //'profile_picture' => 'sometimes|image|max:2048', // Max size 2MB
            //'is_active' => 'sometimes|boolean',
            //'area_id' => 'sometimes|exists:areas,id',
            'personnel_id' => 'sometimes|exists:personnel,id|unique:users,personnel_id,' . $this->route('user')->id,
            'role_id' => 'sometimes|exists:roles,id',
        ];
    }

    public function messages(): array
    {
        return [
            'username.unique' => 'El nombre de usuario ya está en uso.',
            'username.min' => 'El nombre de usuario debe tener al menos 6 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            //'profile_picture.image' => 'La imagen de perfil debe ser una imagen.',
            //'profile_picture.max' => 'La imagen de perfil no puede ser mayor de 2MB.',
            //'area_id.exists' => 'El área seleccionada no es válida.',
            'personnel_id.exists' => 'El personal seleccionado no es válido.',
            'personnel_id.unique' => 'El personal ya tiene una cuenta de usuario asociada.',
            'role_id.exists' => 'El rol seleccionado no existe en la base de datos.',
            'role_id.required' => 'El rol es obligatorio.',
        ];
    }

    public function attributes(): array
    {
        return [
            'username' => 'nombre de usuario',
            'password' => 'contraseña',
            //'profile_picture' => 'imagen de perfil',
            //'is_active' => 'estado activo',
            //'area_id' => 'área',
            'personnel_id' => 'personal',
            'role_id' => 'rol',
        ];
    }

    public function prepareForValidation(): void
    {
        if ($this->has('password')) {
            $this->merge([
                'password' => (string) $this->input('password'),
            ]);
        }

        if ($this->has('password_confirmation')) {
            $this->merge([
                'password_confirmation' => (string) $this->input('password_confirmation'),
            ]);
        }
    }

    public function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'ok' => false,
            'message' => 'Validation errors',
            'errors' => $validator->errors(),
        ], 422));
    }


}
