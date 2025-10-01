<?php

namespace Tests\Feature\Transference;

use App\Models\Transference;
use Domain\Transference\Exception\TransferenceForbidden;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TransferenceTest extends TestCase
{
    /**
     * @return void
     */
    public function gatewayRequestFailed(): void
    {
        Http::fake([
            '*' => Http::response(
                '{"status": "fail","data": {"authorization": false}}',
                JsonResponse::HTTP_FORBIDDEN
            ),
        ]);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->transference = Transference::factory()->payerWithCredit(150)->make();
    }

    public function test_transference_responds_with_http_created(): void
    {
        $this->mockGatewaySuccessful();
        $this->withoutExceptionHandling()
            ->postJson(
                route('transference', $this->transference->only(['payer_id', 'payee_id', 'amount']))
            )->assertStatus(JsonResponse::HTTP_CREATED);
    }

    public function test_transference_must_create_a_transference(): void
    {
        $this->mockGatewaySuccessful();
        $this->withoutExceptionHandling()
            ->postJson(
                route('transference', $this->transference->only(['payer_id', 'payee_id', 'amount']))
            );

        $this->assertDatabaseHas(
            'transferences', $this->transference->only(['payer_id', 'payee_id', 'amount'])
        );
    }

    public function test_transference_responds_with_transference(): void
    {
        $this->mockGatewaySuccessful();
        $this->withoutExceptionHandling()
            ->postJson(
                route('transference', $this->transference->only(['payer_id', 'payee_id', 'amount']))
            )->assertJsonFragment($this->transference->only(['payer_id', 'payee_id', 'amount']));
    }

    public function test_transference_requires_a_payee(): void
    {
        $this->mockGatewaySuccessful();
        $this->postJson(
            route('transference', array_merge($this->transference->only(['payer_id', 'payee_id', 'amount']), ['payee_id' => null]))
        )->assertJsonValidationErrorFor('payee_id');
    }

    public function test_transference_payee_must_exists_in_users_table(): void
    {
        $this->mockGatewaySuccessful();
        $this->postJson(
            route('transference', array_merge($this->transference->only(['payer_id', 'payee_id', 'amount']), ['payee_id' => 1234]))
        )->assertJsonValidationErrorFor('payee_id');
    }

    public function test_transference_requires_a_payer(): void
    {
        $this->mockGatewaySuccessful();
        $this->postJson(
            route('transference', array_merge($this->transference->only(['payer_id', 'payee_id', 'amount']), ['payer_id' => null]))
        )->assertJsonValidationErrorFor('payer_id');
    }

    public function test_transference_payer_must_exists_in_users_table(): void
    {
        $this->mockGatewaySuccessful();
        $this->postJson(
            route('transference', array_merge($this->transference->only(['payer_id', 'payee_id', 'amount']), ['payer_id' => 1234]))
        )->assertJsonValidationErrorFor('payer_id');
    }

    public function test_transference_requires_a_amount(): void
    {
        $this->mockGatewaySuccessful();
        $this->postJson(
            route('transference', array_merge($this->transference->only(['payer_id', 'payee_id', 'amount']), ['amount' => null]))
        )->assertJsonValidationErrorFor('amount');
    }

    public function test_transference_amount_must_be_numeric(): void
    {
        $this->mockGatewaySuccessful();
        $this->postJson(
            route('transference', array_merge($this->transference->only(['payer_id', 'payee_id', 'amount']), ['amount' => 'string']))
        )->assertJsonValidationErrorFor('amount');
    }

    public function test_transference_amount_must_be_greater_than_zero(): void
    {
        $this->mockGatewaySuccessful();
        $this->postJson(
            route('transference', array_merge($this->transference->only(['payer_id', 'payee_id', 'amount']), ['amount' => 0.0]))
        )->assertJsonValidationErrorFor('amount');
    }

    public function test_must_discount_balance_from_payer_wallet(): void
    {
        $this->mockGatewaySuccessful();
        $this->postJson(route('transference', $this->transference->only(['payer_id', 'payee_id', 'amount'])));

        $updatedBalance = $this->transference->payer->wallet->balance - $this->transference->amount;

        $this->assertDatabaseHas(
            'wallets', ['balance' => $updatedBalance, 'user_id' => $this->transference->payer_id]
        );
    }

    public function test_must_add_balance_to_payee_wallet(): void
    {
        $this->mockGatewaySuccessful();

        $updatedBalance = $this->transference->payee->wallet->balance + $this->transference->amount;
        $this->postJson(route('transference', $this->transference->only(['payer_id', 'payee_id', 'amount'])));

        $this->assertDatabaseHas(
            'wallets', ['balance' => $updatedBalance, 'user_id' => $this->transference->payee_id]
        );
    }

    public function test_must_be_authorized_to_transfer(): void
    {
        $this->mockGatewaySuccessful();

        $this->postJson(route('transference', $this->transference->only(['payer_id', 'payee_id', 'amount'])));

        Http::assertSentCount(1);
    }

    public function test_when_unauthorized_by_gateway_rollback_all_operations(): void
    {
        $this->gatewayRequestFailed();

        $this->postJson(
            route('transference', $this->transference->only(['payer_id', 'payee_id', 'amount']))
        );

        $this->assertDatabaseMissing(
            'transferences', $this->transference->only(['payer_id', 'payee_id', 'amount'])
        );
        $this->assertDatabaseHas(
            'wallets',
            ['balance' => $this->transference->payer->wallet->balance, 'user_id' => $this->transference->payer_id]
        );
        $this->assertDatabaseHas(
            'wallets',
            ['balance' => $this->transference->payee->wallet->balance, 'user_id' => $this->transference->payee_id]
        );
    }

    public function test_when_unauthorized_by_gateway_rollback_all_operations_throws_exception(): void
    {
        $this->gatewayRequestFailed();

        $this->expectException(TransferenceForbidden::class);

        $this->withoutExceptionHandling()
            ->postJson(
                route('transference', $this->transference->only(['payer_id', 'payee_id', 'amount']))
            );
    }
}
