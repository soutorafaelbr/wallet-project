<?php

namespace App\Policies;

use App\Models\Transference;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TransferencePolicy
{
    public function create(User $user): bool
    {
        return $user->isPersonalAccount();
    }
}
