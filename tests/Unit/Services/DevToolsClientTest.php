<?php

namespace Tests\Unit;

use Domain\Wallet\Service\DevToolsClient;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DevToolsClientTest extends TestCase
{
    private DevToolsClient $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = $this->app[DevToolsClient::class];
    }

    public function test_hits_endpoint(): void
    {
        $this->mockGatewaySuccessful();
        $this->service->authorizeTransference();
        Http::assertSentCount(1);
    }
}
