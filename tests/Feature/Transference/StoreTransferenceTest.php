<?php

namespace Tests\Feature\Transference;

use App\Models\Transference;
use Domain\Wallet\DTO\MakeTransferenceDTO;
use Domain\Wallet\Service\StoreTransference;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;

class StoreTransferenceTest extends TestCase
{
    private StoreTransference $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = $this->app[StoreTransference::class];
        $this->transference = Transference::factory()->payerWithCredit(150)->make();
    }

    public function test_stores_transference()
    {
        $this->service->execute(
            new MakeTransferenceDTO(
                $this->transference->payer_id,
                $this->transference->payee_id,
                $this->transference->amount
            )
        );

        $this->assertDatabaseHas(
            'transferences', $this->transference->only(['payer_id', 'payee_id', 'amount'])
        );
    }

    public function test_throws_exception_when_payee_not_found()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->service->execute(
            new MakeTransferenceDTO(
                $this->transference->payer_id,
                12345,
                $this->transference->amount
            )
        );
    }

    public function test_throws_exception_when_payer_not_found()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->service->execute(
            new MakeTransferenceDTO(
                123,
                $this->transference->payee_id,
                $this->transference->amount
            )
        );
    }
}
