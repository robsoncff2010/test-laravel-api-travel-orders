<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'requester_name',
        'destination',
        'departure_date',
        'return_date',
        'status',
    ];

    // Relação: uma ordem pertence a um usuário
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relação: uma ordem pode ter várias notificações
    public function notifications()
    {
        return $this->hasMany(TravelOrderNotification::class);
    }
}
