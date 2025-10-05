<?php

namespace Domain\Wallet\Service;

use App\Jobs\NotifyTransferenceSucceeded;
use App\Models\Transference;
use Domain\Wallet\DTO\MakeTransferenceDTO;
use Illuminate\Support\Facades\DB;

class MakeTransference
{
    public function __construct(
        private readonly StoreTransference $storeTransference,
        private readonly OperatesWalletTransference $walletTransference,
        private readonly AuthorizeTransference $authorizeTransfer
    ) {}

    public function execute(MakeTransferenceDTO $dto): Transference
    {
        return DB::transaction(function () use ($dto) {
            $transference = $this->storeTransference->execute($dto);

            $this->walletTransference->execute($transference);

            $this->authorizeTransfer->execute();

            NotifyTransferenceSucceeded::dispatch();

            return $transference;
        });
    }
}
