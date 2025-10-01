<?php

namespace Tests\Repository;

use App\Models\User;
use Domain\User\Repository\UserRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{

    private UserRepository $userRepository;

    public function test_on_find_or_fail_responds_with_user_instance(): void
    {
        $user = User::factory()->create();
        $this->assertInstanceOf(User::class, $this->userRepository->findOrFail($user->id));
    }


    public function test_on_find_or_fail_throws_exception_when_model_does_not_exists(): void
    {
        $this->expectException(ModelNotFoundException::class);
        $this->assertInstanceOf(User::class, $this->userRepository->findOrFail(12345));
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = $this->app[UserRepository::class];
    }
}
