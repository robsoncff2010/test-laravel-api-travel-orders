<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TravelOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'user_id'        => $this->user_id,
            'requester_name' => $this->requester_name,
            'destination'    => $this->destination,
            'departure_date' => $this->departure_date,
            'return_date'    => $this->return_date,
            'status'         => $this->status,
            'created_at'     => $this->created_at,
            'updated_at'     => $this->updated_at,
            'user' => [
                'id'    => $this->user?->id,
                'name'  => $this->user?->name,
                'email' => $this->user?->email,
            ],
            'notifications' => $this->notifications->map(function ($notification) {
                return [
                    'id'         => $notification->id,
                    'message'    => $notification->message,
                    'status'     => $notification->type,
                    'created_at' => $notification->created_at,
                ];
            }),
        ];

    }
}
