<?php

namespace Domain\Wallet\Service;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;

class DevToolsClient
{
    public function __construct(private readonly PendingRequest $httpClient) {}

    public function authorizeTransference(): Response
    {
        return $this->httpClient->get('/v2/authorize');
    }

    public function notifyUsers(): Response
    {
        return $this->httpClient->post('/v1/notify');
    }
}
