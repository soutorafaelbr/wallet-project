<?php

namespace Tests\Feature\Transference;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

class TransferenceTest extends TestCase
{
    public function test_transference_responds_with_http_created(): void
    {
        $payer = User::factory()->create();
        $payee = User::factory()->create();

        $this->postJson(
            route('transference.store', ['payer_id' => $payer->id, 'payee_id' => $payee->id, 'amount' => 100])
        )->assertStatus(JsonResponse::HTTP_CREATED);
    }

}
