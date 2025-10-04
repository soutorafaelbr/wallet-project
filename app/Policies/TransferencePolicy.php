<?php

namespace App\Policies;

use App\Models\Transference;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TransferencePolicy
{
    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, Transference $transference): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return $user->isPersonalAccount();
    }

    public function update(User $user, Transference $transference): bool
    {
        return false;
    }

    public function delete(User $user, Transference $transference): bool
    {
        return false;
    }

    public function restore(User $user, Transference $transference): bool
    {
        return false;
    }

    public function forceDelete(User $user, Transference $transference): bool
    {
        return false;
    }
}
