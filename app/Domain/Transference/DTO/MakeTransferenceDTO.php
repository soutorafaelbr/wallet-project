<?php

namespace Domain\Transference\DTO;

class MakeTransferenceDTO
{
    public string $payerId;
    public string $payeeId;
    public float $amount;

    public function __construct(string $payerId, string $payeeId, float $amount)
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
