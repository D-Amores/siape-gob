<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class LoginUserRequest extends FormRequest
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
            'username'=> 'required|string|max:255',
            'password'=> 'required|string|max:255',
            'remember' => 'sometimes|boolean'
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'El campo usuario es obligatorio.',
            'username.string' => 'El campo usuario debe ser una cadena de texto.',
            'username.max' => 'El campo usuario no debe exceder los 255 caracteres.',
            'password.required' => 'El campo contraseña es obligatorio.',
            'password.string' => 'El campo contraseña debe ser una cadena de texto.',
            'password.max' => 'El campo contraseña no debe exceder los 255 caracteres.',
            'remember.boolean' => 'El campo recordar debe ser verdadero o falso.'
        ];
    }

    public function attributes(): array
    {
        return [
            'username' => 'usuario',
            'password' => 'contraseña',
            'remember' => 'recordar'
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'username' => $this->username,
            'password' => $this->password,
            'remember' => $this->boolean('remember')
        ]);
    }

    public function failedValidation(Validator $validator)
    {
        $response = [
            'ok' => false,
            'message' => 'Error de validación',
            'errors' => $validator->errors()
        ];

        throw new HttpResponseException(response()->json($response, 422));
    }
}
