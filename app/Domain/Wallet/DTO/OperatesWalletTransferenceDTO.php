<?php

namespace Domain\Wallet\DTO;

class OperatesWalletTransferenceDTO
{
    public function __construct(
        public readonly float $amount,
        public readonly int $userId,
    ) {}
}
