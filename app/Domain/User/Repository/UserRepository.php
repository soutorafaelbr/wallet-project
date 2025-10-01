<?php

namespace Domain\User\Repository;

use App\Models\User;

class UserRepository
{

    public function __construct(private readonly User $user)
    {
    }

    public function findOrFail(int $id): User
    {
        return $this->user->query()->findOrFail($id);
    }
}
