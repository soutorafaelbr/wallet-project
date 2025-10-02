<?php

namespace Domain\Wallet\Service;

use App\Models\Transference;
use Domain\Wallet\DTO\CheckAvailableFundsDTO;
use Domain\Wallet\DTO\OperatesWalletTransferenceDTO;
use Domain\Wallet\Exception\InsufficientFunds;
use Domain\Wallet\Exception\OperationFailed;
use Domain\Wallet\Repository\WalletRepository;
use Illuminate\Support\Facades\DB;

class OperatesWalletTransference
{
    public function __construct(private readonly WalletRepository $walletRepository)
    {
    }

    public function execute(Transference $transference): void
    {
        DB::beginTransaction();
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
            DB::rollBack();
            throw new OperationFailed();
        }
        DB::commit();
    }
}
