<?php

namespace App\Providers;

use App\Domain\Wallet\Policies\TransferencePolicy;
use App\Models\Transference;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Transference::class, TransferencePolicy::class);
    }
}
