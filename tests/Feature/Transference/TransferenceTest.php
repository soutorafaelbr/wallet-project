<?php

namespace Tests\Feature\Transference;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

class TransferenceTest extends TestCase
{
    public function test_transference_responds_with_http_created(): void
    {
        $transference = Transaction::factory()->make();

        $this->withoutExceptionHandling()
            ->postJson(
                route('transference', $transference->toArray())
            )->assertStatus(JsonResponse::HTTP_CREATED);
    }

    public function test_transference_must_create_a_transference(): void
    {
        $transference = Transaction::factory()->make();

        $this->withoutExceptionHandling()
            ->postJson(
                route('transference', $transference->toArray())
            );

        $this->assertDatabaseHas('transactions', $transference->toArray());
    }

    public function test_transference_responds_with_transference(): void
    {
        $transference = Transaction::factory()->make();

        $this->withoutExceptionHandling()
            ->postJson(
                route('transference', $transference->toArray())
            )->assertJsonFragment($transference->toArray());
    }

    public function test_transference_requires_a_payee(): void
    {
        $transference = Transaction::factory()->make();

        $this->postJson(
            route('transference', array_merge($transference->toArray(), ['payee_id' => null]))
        )->assertJsonValidationErrorFor('payee_id');
    }

    public function test_transference_payee_must_exists_in_users_table(): void
    {
        $transference = Transaction::factory()->make();

        $this->postJson(
            route('transference', array_merge($transference->toArray(), ['payee_id' => 1234]))
        )->assertJsonValidationErrorFor('payee_id');
    }

    public function test_transference_requires_a_payer(): void
    {
        $transference = Transaction::factory()->make();

        $this->postJson(
            route('transference', array_merge($transference->toArray(), ['payer_id' => null]))
        )->assertJsonValidationErrorFor('payer_id');
    }

    public function test_transference_payer_must_exists_in_users_table(): void
    {
        $transference = Transaction::factory()->make();

        $this->postJson(
            route('transference', array_merge($transference->toArray(), ['payer_id' => 1234]))
        )->assertJsonValidationErrorFor('payer_id');
    }

    public function test_transference_requires_a_amount(): void
    {
        $transference = Transaction::factory()->make();

        $this->postJson(
            route('transference', array_merge($transference->toArray(), ['amount' => null]))
        )->assertJsonValidationErrorFor('amount');
    }

    public function test_transference_amount_must_be_numeric(): void
    {
        $transference = Transaction::factory()->make();

        $this->postJson(
            route('transference', array_merge($transference->toArray(), ['amount' => 'string']))
        )->assertJsonValidationErrorFor('amount');
    }
}
