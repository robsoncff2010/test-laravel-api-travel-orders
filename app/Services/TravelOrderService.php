<?php

namespace App\Services;

use App\Models\TravelOrder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Events\TravelOrderStatusChanged;
use App\Exceptions\Domain\TravelOrder\InvalidOrderStatusException;

class TravelOrderService
{
    // Criar uma nova ordem de viagem
    public function create(array $data, $user): TravelOrder
    {
        return TravelOrder::create([
            'user_id'        => $user->id,
            'requester_name' => $user->name,
            'destination'    => $data['destination'],
            'departure_date' => $data['departure_date'],
            'return_date'    => $data['return_date'],
            'status'         => $data['status'] ?? 'solicitado',
        ]);
    }

    // Listar ordens do usuário autenticado com filtros
    public function list($user, array $filters = []): LengthAwarePaginator
    {
        $query = TravelOrder::with(['user', 'notifications'])
            ->where('user_id', $user->id);

        // Filtro por status
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Filtro por destino
        if (!empty($filters['destination'])) {
            $query->where('destination', 'like', '%' . $filters['destination'] . '%');
        }

        // Filtro por período (datas de viagem)
        if (!empty($filters['from']) && !empty($filters['to'])) {
            $query->whereBetween('departure_date', [$filters['from'], $filters['to']])
                  ->orWhereBetween('return_date', [$filters['from'], $filters['to']]);
        }

        return $query->orderByDesc('created_at')->paginate(10);
    }

    // Buscar uma ordem específica
    public function find(int $id): TravelOrder
    {
        return TravelOrder::with(['user', 'notifications'])->findOrFail($id);
    }

    // Atualizar status da ordem
    public function updateStatus(int $id, string $status): TravelOrder
    {
        $order     = TravelOrder::findOrFail($id);
        $oldStatus = $order->status;

        // Usuário solicitante não pode aprovar ou cancelar
        // Só pode cancelar se estiver aprovado
        if ($status === TravelOrder::CANCELLED && $order->status !== TravelOrder::APPROVED) {
            throw new InvalidOrderStatusException('Só é possível cancelar ordens aprovadas.');
        }

        $order->update(['status' => $status]);

        event(new TravelOrderStatusChanged($order, $oldStatus, $status));

        return $order->fresh(['user', 'notifications']);
    }
}