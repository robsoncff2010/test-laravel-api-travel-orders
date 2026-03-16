<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'O e-mail é obrigatório.',
            'email.string'   => 'O e-mail deve ser um texto válido.',
            'email.email'    => 'O e-mail deve estar em um formato válido.',

            'password.required' => 'A senha é obrigatória.',
            'password.string'   => 'A senha deve ser um texto válido.',
            'password.min'      => 'A senha deve ter pelo menos 6 caracteres.',
        ];
    }
}