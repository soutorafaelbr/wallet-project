<?php

namespace App\Http\Requests\Transference;

use Illuminate\Foundation\Http\FormRequest;

class MakeTransferenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payee_id' => 'required|exists:users,id',
            'payer_id' => 'required|exists:users,id',
            'amount' => 'required|numeric',
        ];
    }
}
