<?php

namespace Domain\Wallet\Service;

use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\Response;

class DevToolsClient
{

    public function __construct(private readonly Factory $httpClient)
    {
    }

    public function authorizeTransference(): Response
    {
        return $this->httpClient->get('/v2/authorize');
    }
}
