<?php

namespace Domain\Transaction\Action;

use App\Http\Requests\Transference\MakeTransferenceRequest;
use App\Models\Transaction;

class MakeTransference
{
    public function execute(MakeTransferenceRequest $request): Transaction
    {
        $transaction = Transaction::query()->create($request->validated());

        if ($transaction->payer->wallet->balance < $transaction->amount) {
            throw new \Exception('Insufficient funds');
        }
        $transaction->payer->wallet->decrement('balance', $transaction->amount);
        $transaction->payee->wallet->increment('balance', $transaction->amount);

        return $transaction;
    }
}
