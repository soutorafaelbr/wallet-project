<?php

namespace App\Policies;

use App\Models\User;

class TransferencePolicy
{
    public function create(User $user): bool
    {
        return $user->isPersonalAccount();
    }
}
