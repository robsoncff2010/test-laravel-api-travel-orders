<?php

namespace App\Providers;

use App\Models\TravelOrder;
use App\Policies\TravelOrderPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        TravelOrder::class => TravelOrderPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}