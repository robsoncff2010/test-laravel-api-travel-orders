<?php

namespace App\Http\Controllers\Api\V1\TravelOrder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\TravelOrderResource;
use App\Services\TravelOrderService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\TravelOrder\StoreTravelOrderRequest;
use App\Http\Requests\TravelOrder\UpdateTravelOrderStatusRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TravelOrderController extends Controller
{
    use AuthorizesRequests;
    
    protected TravelOrderService $travelOrderService;

    public function __construct(TravelOrderService $travelOrderService)
    {
        $this->travelOrderService = $travelOrderService;
    }

    // Listar pedidos (com filtros opcionais)
    public function index(Request $request): JsonResponse
    {
        $orders = $this->travelOrderService->list($request->user(), $request->all());

        return response()->json([
            'success' => true,
            'message' => 'Lista de Pedidos recuperada com sucesso',
            'data'    => TravelOrderResource::collection($orders),
            'meta'    => [
                'total'        => $orders->total(),
                'per_page'     => $orders->perPage(),
                'current_page' => $orders->currentPage(),
                'last_page'    => $orders->lastPage(),
            ],
        ]);
    }

    // Criar pedido de viagem
    public function store(StoreTravelOrderRequest $request): JsonResponse
    {
        $order = $this->travelOrderService->create($request->all(), $request->user());

        return response()->json([
            'success' => true,
            'message' => 'Pedido criada com sucesso',
            'data'    => new TravelOrderResource($order),
        ], 201);
    }

    // Detalhar pedido
    public function show(int $id): JsonResponse
    {
        $order = $this->travelOrderService->find($id);

        $this->authorize('view', $order);

        return response()->json([
            'success' => true,
            'message' => 'Detalhes do pedido recuperados com sucesso',
            'data'    => new TravelOrderResource($order),
        ]);
    }

    // Atualizar status do pedido (aprovado ou cancelado)
    public function updateStatus(UpdateTravelOrderStatusRequest $request, int $id): JsonResponse
    {
        $order = $this->travelOrderService->find($id);

        $this->authorize('updateStatus', $order);

        $order = $this->travelOrderService->updateStatus($id, $request->input('status'));

        return response()->json([
            'success' => true,
            'message' => 'Status do pedido atualizado com sucesso',
            'data'    => new TravelOrderResource($order),
        ]);
    }
}