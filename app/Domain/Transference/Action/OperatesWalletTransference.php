<?php

namespace Domain\Transference\Action;

use App\Models\Transference;
use Domain\Transference\DTO\CheckAvailableFundsDTO;
use Domain\Transference\DTO\OperatesWalletTransferenceDTO;
use Domain\Transference\Exception\InsufficientFunds;
use Domain\Transference\Repository\WalletRepository;

class OperatesWalletTransference
{
    public function __construct(private readonly WalletRepository $walletRepository)
    {
    }

    public function execute(Transference $transference): void
    {
        $hasEnoughFunds = $this->walletRepository->hasEnoughFunds(
            new CheckAvailableFundsDTO($transference->payer_id, $transference->amount)
        );

        if (!$hasEnoughFunds) {
            throw new InsufficientFunds();
        }

        $balanceDecreased = $this->walletRepository->decreaseBalance(
            new OperatesWalletTransferenceDTO($transference->amount, $transference->payer_id)
        );

        $balanceIncreased = $this->walletRepository->increaseBalance(
            new OperatesWalletTransferenceDTO($transference->amount, $transference->payee_id)
        );

        if (!$balanceDecreased || !$balanceIncreased) {
            throw new \Exception('Failed to perform operation');
        }
    }
}
