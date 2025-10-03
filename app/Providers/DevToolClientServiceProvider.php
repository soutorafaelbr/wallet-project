<?php

namespace App\Providers;

use Domain\Wallet\Service\DevToolsClient;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class DevToolClientServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(
            DevToolsClient::class,
            fn () => new DevToolsClient(
                Http::baseUrl(config('services.dev-tools.url'))
            )
        );
    }

    public function boot(): void
    {
        //
    }
}
