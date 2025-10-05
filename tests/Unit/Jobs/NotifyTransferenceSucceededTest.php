<?php

namespace Tests\Jobs;

use App\Jobs\NotifyTransferenceSucceeded;
use App\Models\Transference;
use Domain\Wallet\Service\DevToolsClient;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class NotifyTransferenceSucceededTest extends TestCase
{
    public function mockNotifyEndpoint(): void
    {
        Http::fake([
            '*' => Http::response('{}', JsonResponse::HTTP_INTERNAL_SERVER_ERROR),
        ]);
    }

    public function test_calls_endpoint_to_notify(): void
    {
        $this->mockNotifyEndpoint();
        $transference = Transference::factory()->create();
        NotifyTransferenceSucceeded::dispatchSync($transference);
        Http::assertSentCount(1);
    }

    public function test_calls_release(): void
    {
        $this->mockNotifyEndpoint();

        $mock = \Mockery::mock(NotifyTransferenceSucceeded::class)
            ->makePartial();

        $mock->shouldReceive('release')
            ->once();

        $mock->handle(app(DevToolsClient::class));
    }
}
