<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService) {}

    public function register(RegisterRequest $request)
    {
        $user = $this->authService->register($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Usuário registrado com sucesso',
            'user'    => new UserResource($user),
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        $token       = $this->authService->login($credentials);

        if (! $token) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciais inválidas'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'Login realizado com sucesso',
            'token'   => $token,
            'user'    => new UserResource(auth()->user()),
        ]);
    }


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'success' => true,
            'message' => 'Logout realizado com sucesso'
        ]);
    }

    public function me(Request $request)
    {
        return new UserResource($request->user());
    }
}
