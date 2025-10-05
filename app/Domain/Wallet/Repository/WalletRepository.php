<?php

namespace Domain\Wallet\Repository;

use App\Models\Wallet;
use Domain\Wallet\DTO\CheckAvailableFundsDTO;
use Domain\Wallet\DTO\OperatesWalletTransferenceDTO;

class WalletRepository
{
    public function __construct(private readonly Wallet $wallet) {}

    public function increaseBalance(OperatesWalletTransferenceDTO $dto): bool
    {
        return (bool) $this->wallet->query()
            ->where('user_id', $dto->userId)
            ->increment('balance', $dto->amount);
    }

    public function decreaseBalance(OperatesWalletTransferenceDTO $dto): bool
    {
        return (bool) $this->wallet->query()
            ->where('user_id', $dto->userId)
            ->decrement('balance', $dto->amount);
    }

    public function hasEnoughFunds(CheckAvailableFundsDTO $dto): bool
    {
        return $this->wallet->query()
            ->where('user_id', $dto->payerId)
            ->where('balance', '>=', $dto->fundsToTransfer)
            ->exists();
    }
}
