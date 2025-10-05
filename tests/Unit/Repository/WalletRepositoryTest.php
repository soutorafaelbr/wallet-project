<?php

namespace Tests\Unit\Repository;

use App\Models\User;
use Domain\Wallet\DTO\OperatesWalletTransferenceDTO;
use Domain\Wallet\Repository\WalletRepository;
use Tests\TestCase;

class WalletRepositoryTest extends TestCase
{
    private WalletRepository $repository;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->app[WalletRepository::class];
        $this->user = User::factory()->create();
        $this->user->wallet->update(['balance' => 10]);
    }

    public function test_increases_balance()
    {
        $amount = 10.00;
        $this->repository->increaseBalance(new OperatesWalletTransferenceDTO($amount, $this->user->id));
        $this->assertEquals(
            $this->user->wallet->balance + $amount,
            $this->user->wallet->fresh()->balance
        );
    }

    public function test_decreases_balance()
    {
        $amount = 10.00;
        $this->repository->decreaseBalance(new OperatesWalletTransferenceDTO($amount, $this->user->id));

        $this->assertEquals(
            $this->user->wallet->balance - $amount,
            $this->user->wallet->fresh()->balance
        );
    }
}
