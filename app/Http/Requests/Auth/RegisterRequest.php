<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome é obrigatório.',
            'name.string'   => 'O nome deve ser um texto válido.',
            'name.max'      => 'O nome não pode ultrapassar 255 caracteres.',

            'email.required' => 'O e-mail é obrigatório.',
            'email.string'   => 'O e-mail deve ser um texto válido.',
            'email.email'    => 'O e-mail deve estar em um formato válido.',
            'email.unique'   => 'Este e-mail já está cadastrado.',

            'password.required'  => 'A senha é obrigatória.',
            'password.string'    => 'A senha deve ser um texto válido.',
            'password.min'       => 'A senha deve ter pelo menos 6 caracteres.',
            'password.confirmed' => 'A confirmação da senha não confere.',
        ];
    }
}