<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\TravelOrderStatusChanged;
use App\Listeners\CreateTravelOrderNotification;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        TravelOrderStatusChanged::class => [
            CreateTravelOrderNotification::class,
        ],
    ];
}
