<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TravelOrder;

class TravelOrderPolicy
{
    // Qualquer usuário autenticado pode criar seus próprios pedidos
    public function create(User $user): bool
    {
        return true;
    }

    // Cada usuário só pode visualizar seus próprios pedidos
    public function view(User $user, TravelOrder $order): bool
    {
        return $order->user_id === $user->id;
    }

    // Cada usuário só pode editar seus próprios pedidos
    public function update(User $user, TravelOrder $order): bool
    {
        return $order->user_id === $user->id;
    }

    // O usuário solicitante não pode alterar o status do próprio pedido
    // Apenas outro usuário pode aprovar/cancelar
    public function updateStatus(User $user, TravelOrder $order): bool
    {
        return $order->user_id !== $user->id;
    }
}