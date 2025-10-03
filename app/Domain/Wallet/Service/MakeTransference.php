<?php

namespace Domain\Wallet\Service;

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
        private readonly StoreTransference $storeTransference,
        private readonly OperatesWalletTransference $operatesWalletTransference,
        private readonly AuthorizeTransference $authorizeTransference
    )
    {
    }

    public function execute(MakeTransferenceDTO $dto): Transference
    {
        return DB::transaction(function () use ($dto) {
            $transference = $this->storeTransference->execute($dto);

            $this->operatesWalletTransference->execute($transference);

            $this->authorizeTransference->execute();

            return $transference;
        });
    }
}
