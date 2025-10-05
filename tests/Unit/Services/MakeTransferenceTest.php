<?php

namespace Tests\Unit;

use App\Jobs\NotifyTransferenceSucceeded;
use App\Models\Transference;
use App\Models\User;
use Domain\Wallet\DTO\MakeTransferenceDTO;
use Domain\Wallet\Exception\InsufficientFunds;
use Domain\Wallet\Service\MakeTransference;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class MakeTransferenceTest extends TestCase
{
    private Transference $transference;

    protected function setUp(): void
    {
        parent::setUp();
        $this->transference = Transference::factory()->payerWithCredit(150)->make();
    }

    public function test_throws_exception_when_payer_id_is_not_a_valid_user_id()
    {
        $payee = User::factory()->create();
        $dto = new MakeTransferenceDto(123, $payee->id, 10.00);
        $this->expectException(ModelNotFoundException::class);
        $this->app[MakeTransference::class]->execute($dto);
    }

    public function test_throws_exception_when_payee_id_is_not_a_valid_user_id()
    {
        $payer = User::factory()->create();
        $dto = new MakeTransferenceDto($payer->id, 123, 10.00);
        $this->expectException(ModelNotFoundException::class);
        $this->app[MakeTransference::class]->execute($dto);
    }

    public function test_throws_exception_when_funds_are_insufficient()
    {
        $dto = new MakeTransferenceDto($this->transference->payer_id, $this->transference->payee_id, 300.00);
        $this->expectException(InsufficientFunds::class);
        $this->app[MakeTransference::class]->execute($dto);
    }

    public function test_responds_with_transference(): void
    {
        $this->mockGatewaySuccessful();
        $dto = new MakeTransferenceDto($this->transference->payer_id, $this->transference->payee_id, 10.00);
        $this->assertInstanceOf(Transference::class,  $this->app[MakeTransference::class]->execute($dto));
    }

    public function test_notifies_users(): void
    {
        $this->mockGatewaySuccessful();
        $this->mockNotificationSuccessful();
        Queue::fake();
        $dto = new MakeTransferenceDto($this->transference->payer_id, $this->transference->payee_id, 10.00);
        $this->app[MakeTransference::class]->execute($dto);
        Queue::assertPushed(NotifyTransferenceSucceeded::class);
    }
}
