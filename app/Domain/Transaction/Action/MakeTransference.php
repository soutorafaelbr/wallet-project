<?php

namespace Domain\Transaction\Action;

use App\Http\Requests\Transference\MakeTransferenceRequest;
use App\Models\Transference;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class MakeTransference
{
    public function execute(MakeTransferenceRequest $request): Transference
    {
        return DB::transaction(function () use ($request) {
            $transference = Transference::query()->create($request->validated());

            if ($transference->payer->wallet->balance < $transference->amount) {
                throw new \Exception('Insufficient funds');
            }
            $transference->payer->wallet->decrement('balance', $transference->amount);
            $transference->payee->wallet->increment('balance', $transference->amount);

            $response = Http::get('https://util.devi.tools/api/v2/authorize');
            if ($response->failed()) {
                throw new \Exception('Error on authorization');
            }

            return $transference;
        });
    }
}
