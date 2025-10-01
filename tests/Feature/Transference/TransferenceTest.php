<?php

namespace Tests\Feature\Transference;

use App\Models\Transference;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

class TransferenceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->transference = Transference::factory()->payerWithCredit(150)->make();
    }
    public function test_transference_responds_with_http_created(): void
    {
        $this->withoutExceptionHandling()
            ->postJson(
                route('transference', $this->transference->only(['payer_id', 'payee_id', 'amount']))
            )->assertStatus(JsonResponse::HTTP_CREATED);
    }

    public function test_transference_must_create_a_transference(): void
    {
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
        $this->withoutExceptionHandling()
            ->postJson(
                route('transference', $this->transference->only(['payer_id', 'payee_id', 'amount']))
            )->assertJsonFragment($this->transference->only(['payer_id', 'payee_id', 'amount']));
    }

    public function test_transference_requires_a_payee(): void
    {
        $this->postJson(
            route('transference', array_merge($this->transference->only(['payer_id', 'payee_id', 'amount']), ['payee_id' => null]))
        )->assertJsonValidationErrorFor('payee_id');
    }

    public function test_transference_payee_must_exists_in_users_table(): void
    {
        $this->postJson(
            route('transference', array_merge($this->transference->only(['payer_id', 'payee_id', 'amount']), ['payee_id' => 1234]))
        )->assertJsonValidationErrorFor('payee_id');
    }

    public function test_transference_requires_a_payer(): void
    {
        $this->postJson(
            route('transference', array_merge($this->transference->only(['payer_id', 'payee_id', 'amount']), ['payer_id' => null]))
        )->assertJsonValidationErrorFor('payer_id');
    }

    public function test_transference_payer_must_exists_in_users_table(): void
    {
        $this->postJson(
            route('transference', array_merge($this->transference->only(['payer_id', 'payee_id', 'amount']), ['payer_id' => 1234]))
        )->assertJsonValidationErrorFor('payer_id');
    }

    public function test_transference_requires_a_amount(): void
    {
        $this->postJson(
            route('transference', array_merge($this->transference->only(['payer_id', 'payee_id', 'amount']), ['amount' => null]))
        )->assertJsonValidationErrorFor('amount');
    }

    public function test_transference_amount_must_be_numeric(): void
    {
        $this->postJson(
            route('transference', array_merge($this->transference->only(['payer_id', 'payee_id', 'amount']), ['amount' => 'string']))
        )->assertJsonValidationErrorFor('amount');
    }

    public function test_transference_amount_must_be_greater_than_zero(): void
    {
        $this->postJson(
            route('transference', array_merge($this->transference->only(['payer_id', 'payee_id', 'amount']), ['amount' => 0.0]))
        )->assertJsonValidationErrorFor('amount');
    }

    public function test_must_discount_balance_from_payer_wallet(): void
    {
        $this->postJson(route('transference', $this->transference->only(['payer_id', 'payee_id', 'amount'])));

        $updatedBalance = $this->transference->payer->wallet->balance - $this->transference->amount;

        $this->assertDatabaseHas(
            'wallets', ['balance' => $updatedBalance, 'user_id' => $this->transference->payer_id]
        );
    }
}
