<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TravelOrder;

class TravelOrderPolicy
{
    // Qualquer usuário autenticado pode criar suas próprias ordens
    public function create(User $user): bool
    {
        return true;
    }

    // Cada usuário só pode visualizar suas próprias ordens
    public function view(User $user, TravelOrder $order): bool
    {
        return $order->user_id === $user->id;
    }

    // O usuário solicitante não pode alterar o status da própria ordem
    // Apenas outro usuário pode aprovar/cancelar
    public function updateStatus(User $user, TravelOrder $order): bool
    {
        return $order->user_id !== $user->id;
    }
}