<?php

namespace App\Listeners;

use App\Events\TravelOrderStatusChanged;

class CreateTravelOrderNotification
{
    public function handle(TravelOrderStatusChanged $event): void
    {
        $event->travelOrder->notifications()->create([
            'message' => "Status alterado de '{$event->oldStatus}' para '{$event->newStatus}'",
            'type'    => $event->newStatus,
            'user_id' => auth()->id(),
        ]);
    }
}