<?php

namespace App\Jobs;

use App\Models\Transference;
use Domain\Wallet\Service\DevToolsClient;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class NotifyTransferenceSucceeded implements ShouldQueue
{
    use Queueable;

    public $tries = 3;

    public function __construct(private readonly Transference $transference) {}

    public function handle(DevToolsClient $devToolsClient): void
    {
        $response = $devToolsClient->notifyUsers();
        if ($response->failed()) {
            $this->release(now()->addMinutes(2));
        }
    }
}
