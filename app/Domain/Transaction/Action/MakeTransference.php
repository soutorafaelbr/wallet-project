<?php

namespace Domain\Transaction\Action;

use App\Models\Transference;
use App\Models\User;
use Domain\Transaction\DTO\MakeTransferenceDTO;
use Domain\Transaction\Exception\InsufficientFunds;
use Domain\Transaction\Exception\TransferenceForbidden;
use Domain\User\Repository\UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class MakeTransference
{

    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    public function execute(MakeTransferenceDTO $dto): Transference
    {
        return DB::transaction(function () use ($dto) {
            $payer = $this->userRepository->findOrFail($dto->payerId);

            $payee = $this->userRepository->findOrFail($dto->payeeId);

            $transference = Transference::query()->create([
                'amount' => $dto->amount,
                'payer_id' => $payer->id,
                'payee_id' => $payee->id,
            ]);

            if ($transference->payer->wallet->balance < $transference->amount) {
                throw new InsufficientFunds();
            }

            $transference->payer->wallet->decrement('balance', $transference->amount);
            $transference->payee->wallet->increment('balance', $transference->amount);

            $response = Http::get('https://util.devi.tools/api/v2/authorize');

            if ($response->failed()) {
                throw new TransferenceForbidden();
            }

            return $transference;
        });
    }
}
