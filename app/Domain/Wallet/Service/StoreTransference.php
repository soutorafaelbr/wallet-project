<?php

namespace Domain\Wallet\Service;

use App\Models\Transference;
use Domain\User\Repository\UserRepository;
use Domain\Wallet\DTO\MakeTransferenceDTO;
use Domain\Wallet\Exception\CompanyCannotTransferFunds;
use Domain\Wallet\Repository\TransferenceRepository;

class StoreTransference
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly TransferenceRepository $transferenceRepo
    ) {}

    public function execute(MakeTransferenceDTO $dto): Transference
    {
        $payer = $this->userRepository->findOrFail($dto->payerId);

        if ($payer->cannot('create', Transference::class)) {
            throw new CompanyCannotTransferFunds;
        }

        $payee = $this->userRepository->findOrFail($dto->payeeId);

        return $this->transferenceRepo->create([
            'amount' => $dto->amount,
            'payer_id' => $payer->id,
            'payee_id' => $payee->id,
        ]);
    }
}
