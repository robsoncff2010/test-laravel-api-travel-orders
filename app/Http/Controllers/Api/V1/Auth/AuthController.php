<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService) {}

    // Registra usuário
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->authService->register($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Usuário registrado com sucesso',
            'user'    => new UserResource($user),
        ], 201);
    }

    // Autentica usuário
    public function login(LoginRequest $request): JsonResponse
    {
        $token = $this->authService->login($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Login realizado com sucesso',
            'token'   => $token,
            'user'    => new UserResource(auth()->user()),
        ]);
    }

    // Remove autenticação usuário
    public function logout(): JsonResponse
    {
        $this->authService->logout();

        return response()->json([
            'success' => true,
            'message' => 'Logout realizado com sucesso'
        ]);
    }

    // Retorna usuário autenticado
    public function me(Request $request): UserResource
    {
        return new UserResource($request->user());
    }
}
