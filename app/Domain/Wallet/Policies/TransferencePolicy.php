<?php

namespace App\Domain\Wallet\Policies;

use App\Models\User;

class TransferencePolicy
{
    public function create(User $user): bool
    {
        return $user->isPersonalAccount();
    }
}
