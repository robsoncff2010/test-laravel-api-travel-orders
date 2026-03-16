<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\TravelOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TravelOrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper para autenticar e gerar headers com JWT.
     */
    protected function authHeaders(User $user): array
    {
        $token = auth('api')->login($user);
        return ['Authorization' => "Bearer $token"];
    }

    public function test_user_can_create_travel_order()
    {
        $user = User::factory()->create();

        $response = $this->withHeaders($this->authHeaders($user))
            ->postJson('/api/v1/travel-orders', [
                'destination'    => 'Belo Horizonte',
                'departure_date' => '2026-03-20',
                'return_date'    => '2026-03-30',
            ]);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Pedido criada com sucesso',
                 ]);
    }

    public function test_create_order_fails_without_destination()
    {
        $user = User::factory()->create();

        $response = $this->withHeaders($this->authHeaders($user))
            ->postJson('/api/v1/travel-orders', [
                'departure_date' => '2026-03-20',
                'return_date'    => '2026-03-30',
            ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['destination']);
    }

    public function test_user_can_list_travel_orders()
    {
        $user = User::factory()->create();
        TravelOrder::factory()->create(['user_id' => $user->id]);

        $response = $this->withHeaders($this->authHeaders($user))
            ->getJson('/api/v1/travel-orders');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Lista de Pedidos recuperada com sucesso',
                 ]);
    }

    public function test_user_can_update_order_status()
    {
        // Usuário A cria a ordem
        $creator = User::factory()->create();
        $order   = TravelOrder::factory()->create(['user_id' => $creator->id]);

        // Usuário B autentica
        $approver = User::factory()->create();

        $response = $this->withHeaders($this->authHeaders($approver))
            ->putJson("/api/v1/travel-orders/{$order->id}/status", [
                'status' => 'aprovado',
            ]);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Status do pedido atualizado com sucesso',
                ]);
    }


    public function test_creator_cannot_update_own_order_status()
    {
        $creator = User::factory()->create();
        $order   = TravelOrder::factory()->create(['user_id' => $creator->id]);

        $response = $this->withHeaders($this->authHeaders($creator))
            ->putJson("/api/v1/travel-orders/{$order->id}/status", [
                'status' => 'aprovado',
            ]);

        $response->assertStatus(500);
    }

    public function test_update_order_status_fails_without_status()
    {
        $user = User::factory()->create();
        $order = TravelOrder::factory()->create(['user_id' => $user->id]);

        $response = $this->withHeaders($this->authHeaders($user))
            ->putJson("/api/v1/travel-orders/{$order->id}/status", []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['status']);
    }
}