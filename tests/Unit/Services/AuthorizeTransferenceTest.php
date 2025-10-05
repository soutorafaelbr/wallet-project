<?php

namespace Tests\Unit;

use Domain\Wallet\Exception\TransferenceForbidden;
use Domain\Wallet\Service\DevToolsClient;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AuthorizeTransferenceTest extends TestCase
{
    protected DevToolsClient $service;

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

    public function thows_exception_when_endpoint_fails(): void
    {
        $this->mockGatewayFailed();
        $this->expectException(TransferenceForbidden::class);
        $this->service->authorizeTransference();
    }
}
