<?php

namespace App\Http\Requests\TravelOrder;

use Illuminate\Foundation\Http\FormRequest;

class IndexTravelOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status'      => ['nullable', 'string', 'in:solicitado,aprovado,cancelado'],
            'destination' => ['nullable', 'string', 'max:255'],
            'from'        => ['nullable', 'date'],
            'to'          => ['nullable', 'date', 'after_or_equal:from'],
        ];
    }

    public function messages(): array
    {
        return [
            'status.in'          => 'O status deve ser um dos seguintes valores: solicitado, aprovado ou cancelado.',
            'destination.string' => 'O destino deve ser um texto válido.',
            'destination.max'    => 'O destino não pode ultrapassar 255 caracteres.',
            'from.date'          => 'A data inicial deve ser uma data válida.',
            'to.date'            => 'A data final deve ser uma data válida.',
            'to.after_or_equal'  => 'A data final deve ser igual ou posterior à data inicial.',
        ];
    }
}
