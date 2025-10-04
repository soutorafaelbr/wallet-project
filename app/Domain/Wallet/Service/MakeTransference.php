<?php

namespace Domain\Wallet\Service;

use App\Models\Transference;
use Domain\Wallet\DTO\MakeTransferenceDTO;
use Illuminate\Support\Facades\DB;

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
