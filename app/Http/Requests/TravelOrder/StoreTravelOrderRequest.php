<?php

namespace App\Http\Requests\TravelOrder;

use Illuminate\Foundation\Http\FormRequest;

class StoreTravelOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'destination'     => ['required', 'string', 'max:255'],
            'departure_date'  => ['required', 'date', 'after_or_equal:today'],
            'return_date'     => ['required', 'date', 'after_or_equal:departure_date'],
        ];
    }

    public function messages(): array
    {
        return [
            'destination.required' => 'O destino é obrigatório.',
            'destination.string'   => 'O destino deve ser um texto válido.',
            'destination.max'      => 'O destino não pode ultrapassar 255 caracteres.',

            'departure_date.required'       => 'A data de saída é obrigatória.',
            'departure_date.date'           => 'A data de saída deve ser uma data válida.',
            'departure_date.after_or_equal' => 'A data de saída deve ser hoje ou uma data futura.',

            'return_date.required'       => 'A data de retorno é obrigatória.',
            'return_date.date'           => 'A data de retorno deve ser uma data válida.',
            'return_date.after_or_equal' => 'A data de retorno deve ser igual ou posterior à data de saída.',
        ];
    }
}
