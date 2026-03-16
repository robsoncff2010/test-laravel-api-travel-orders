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
            $query->where(function ($q) use ($filters) {
                // pedidos criados dentro da faixa
                $q->whereBetween('created_at', [$filters['from'], $filters['to']])
                // viagens que caem dentro da faixa
                ->orWhereBetween('departure_date', [$filters['from'], $filters['to']])
                ->orWhereBetween('return_date', [$filters['from'], $filters['to']])
                ->orWhere(function ($q2) use ($filters) {
                    $q2->where('departure_date', '<', $filters['from'])
                        ->where('return_date', '>', $filters['to']);
                });
            });
        } elseif (!empty($filters['from'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('created_at', '>=', $filters['from'])
                ->orWhere('departure_date', '>=', $filters['from'])
                ->orWhere('return_date', '>=', $filters['from']);
            });
        } elseif (!empty($filters['to'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('created_at', '<=', $filters['to'])
                ->orWhere('departure_date', '<=', $filters['to'])
                ->orWhere('return_date', '<=', $filters['to']);
            });
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

        // Não permite o solicitante alterar para o mesmo status, para não ficar redundante
        if ($status === $order->status) {
            throw new InvalidOrderStatusException("Pedido já se encontra com o status de '{$order->status}'");
        }

        // Usuário solicitante não pode aprovar ou cancelar
        // Só pode cancelar se estiver aprovado
        if ($status === TravelOrder::CANCELLED && $order->status !== TravelOrder::APPROVED) {
            throw new InvalidOrderStatusException('Só é possível cancelar pedidos aprovadas.');
        }

        $order->update(['status' => $status]);

        event(new TravelOrderStatusChanged($order, $oldStatus, $status));

        return $order->fresh(['user', 'notifications']);
    }
}