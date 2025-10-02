<?php

namespace Domain\Wallet\Repository;

use App\Models\Wallet;
use Domain\Wallet\DTO\CheckAvailableFundsDTO;
use Domain\Wallet\DTO\OperatesWalletTransferenceDTO;

class WalletRepository
{
    public function __construct(private readonly Wallet $wallet)
    {
    }

    public function increaseBalance(OperatesWalletTransferenceDTO $DTO): bool
    {
        return $this->wallet->query()
            ->where('user_id', $DTO->userId)
            ->increment('balance', $DTO->amount);
    }

    public function decreaseBalance(OperatesWalletTransferenceDTO $DTO): bool
    {
        return $this->wallet->query()
            ->where('id', $DTO->userId)
            ->decrement('balance', $DTO->amount);
    }
    public function hasEnoughFunds(CheckAvailableFundsDTO $DTO): bool
    {
        return $this->wallet->query()
            ->where('user_id', $DTO->payerId)
            ->where('balance', '>=', $DTO->fundsToTransfer)
            ->exists();
    }

}
