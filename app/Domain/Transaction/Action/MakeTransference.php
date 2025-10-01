<?php

namespace Domain\Transaction\Action;

use App\Http\Requests\Transference\MakeTransferenceRequest;
use App\Models\Transference;

class MakeTransference
{
    public function execute(MakeTransferenceRequest $request): Transference
    {
        $transference = Transference::query()->create($request->validated());

        if ($transference->payer->wallet->balance < $transference->amount) {
            throw new \Exception('Insufficient funds');
        }
        $transference->payer->wallet->decrement('balance', $transference->amount);
        $transference->payee->wallet->increment('balance', $transference->amount);

        return $transference;
    }
}
