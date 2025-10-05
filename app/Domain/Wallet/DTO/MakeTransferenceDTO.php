<?php

namespace Domain\Wallet\DTO;

class MakeTransferenceDTO
{
    public int $payerId;

    public int $payeeId;

    public float $amount;

    public function __construct(int $payerId, int $payeeId, float $amount)
    {
        $this->payerId = $payerId;
        $this->payeeId = $payeeId;
        $this->amount = $amount;
    }

    public static function fromValidatedRequest(array $data): self
    {
        return new self($data['payer_id'], $data['payee_id'], $data['amount']);
    }

    public function toArray(): array
    {
        return (array) $this;
    }
}
