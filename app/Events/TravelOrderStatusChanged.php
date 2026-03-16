<?php

namespace App\Events;

use App\Models\TravelOrder;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TravelOrderStatusChanged
{
    use Dispatchable, SerializesModels;

    public $travelOrder;
    public $oldStatus;
    public $newStatus;

    public function __construct(TravelOrder $travelOrder, string $oldStatus, string $newStatus)
    {
        $this->travelOrder = $travelOrder;
        $this->oldStatus   = $oldStatus;
        $this->newStatus   = $newStatus;
    }
}