<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransferenceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'payer_id' => $this->payer_id,
            'payee_id' => $this->payee_id,
            'amount' => $this->amount,
        ];
    }
}
