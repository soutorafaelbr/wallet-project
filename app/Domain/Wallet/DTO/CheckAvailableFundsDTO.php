<?php

namespace Domain\Wallet\DTO;

class CheckAvailableFundsDTO
{
    public function __construct(public readonly int $payerId, public readonly float $fundsToTransfer) {}
}
