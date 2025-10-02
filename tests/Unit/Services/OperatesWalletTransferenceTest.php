<?php

namespace Tests\Unit;

use App\Models\Transference;
use Domain\Wallet\Exception\InsufficientFunds;
use Domain\Wallet\Exception\OperationFailed;
use Domain\Wallet\Repository\WalletRepository;
use Domain\Wallet\Service\OperatesWalletTransference;
use Mockery;
use Mockery\Mock;
use Tests\TestCase;

class OperatesWalletTransferenceTest extends TestCase
{
    protected OperatesWalletTransference $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = $this->app[OperatesWalletTransference::class];
    }

    public function test_throws_exception_when_fund_is_insufficient()
    {
        $this->expectException(InsufficientFunds::class);
        $this->service->execute(Transference::factory()->create());
    }

    public function test_throws_exception_when_increase_fails()
    {
        $walletRepoMock = Mockery::mock(WalletRepository::class);
        $walletRepoMock->shouldReceive('hasEnoughFunds')->andReturn(true);
        $walletRepoMock->shouldReceive('increaseBalance')->andReturn(false);
        $walletRepoMock->shouldReceive('decreaseBalance')->andReturn(true);

        $this->app->bind(WalletRepository::class, fn () => $walletRepoMock);
        $this->expectException(OperationFailed::class);
        $this->app[OperatesWalletTransference::class]->execute(Transference::factory()->create());
    }

    public function test_throws_exception_when_decrease_fails()
    {
        $walletRepoMock = Mockery::mock(WalletRepository::class);
        $walletRepoMock->shouldReceive('hasEnoughFunds')->andReturn(true);
        $walletRepoMock->shouldReceive('increaseBalance')->andReturn(true);
        $walletRepoMock->shouldReceive('decreaseBalance')->andReturn(false);

        $this->app->bind(WalletRepository::class, fn () => $walletRepoMock);
        $this->expectException(OperationFailed::class);
        $this->app[OperatesWalletTransference::class]->execute(Transference::factory()->create());
    }
}
