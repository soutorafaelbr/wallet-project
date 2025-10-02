<?php

namespace Domain\Transference\DTO;

class CheckAvailableFundsDTO
{
    public function __construct(public readonly int $payerId, public readonly float $fundsToTransfer) {}
}
