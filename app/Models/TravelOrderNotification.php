<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelOrderNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'travel_order_id',
        'user_id',
        'type',
        'message',
    ];

    // notificação pertence a uma ordem
    public function travelOrder()
    {
        return $this->belongsTo(TravelOrder::class);
    }

    // notificação pertence a um usuário
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
