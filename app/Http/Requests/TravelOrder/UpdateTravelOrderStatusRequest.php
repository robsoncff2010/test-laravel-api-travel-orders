<?php

namespace App\Http\Requests\TravelOrder;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTravelOrderStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'in:solicitado,aprovado,cancelado'],
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'O status é obrigatório.',
            'status.in'       => 'O status deve ser um dos seguintes valores: solicitado, aprovado ou cancelado.',
        ];
    }
}
