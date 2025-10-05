<?php

namespace Domain\Wallet\Repository;

use App\Models\Transference;

class TransferenceRepository
{
    public function __construct(protected readonly Transference $transference) {}

    public function create(array $data): Transference
    {
        return $this->transference->query()->create($data);
    }
}
