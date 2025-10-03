<?php

namespace Domain\Wallet\Service;

use Domain\Wallet\Exception\TransferenceForbidden;

class AuthorizeTransference
{
    public function __construct(private readonly DevToolsClient $devToolsClient)
    {
    }

    public function execute(): void
    {
        $response = $this->devToolsClient->authorizeTransference();

        if ($response->failed()) {
            throw new TransferenceForbidden();
        }
    }
}
