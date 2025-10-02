<?php

namespace Domain\Wallet\Action;

use App\Models\Transference;
use Domain\Wallet\DTO\MakeTransferenceDTO;
use Domain\Wallet\Exception\TransferenceForbidden;
use Domain\Wallet\Repository\TransferenceRepository;
use Domain\User\Repository\UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class MakeTransference
{

    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly TransferenceRepository $transferenceRepository,
        private readonly OperatesWalletTransference $operatesWalletTransference
    )
    {
    }

    public function execute(MakeTransferenceDTO $dto): Transference
    {
        return DB::transaction(function () use ($dto) {
            $payer = $this->userRepository->findOrFail($dto->payerId);

            $payee = $this->userRepository->findOrFail($dto->payeeId);

            $transference = $this->transferenceRepository->create([
                'amount' => $dto->amount,
                'payer_id' => $payer->id,
                'payee_id' => $payee->id,
            ]);

            $this->operatesWalletTransference->execute($transference);

            $response = Http::get('https://util.devi.tools/api/v2/authorize');

            if ($response->failed()) {
                throw new TransferenceForbidden();
            }

            return $transference;
        });
    }
}
