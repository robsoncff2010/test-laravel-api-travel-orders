<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Exceptions\Domain\Auth\InvalidCredentialsException;

class AuthService
{
    // Registrar usuário
    public function register(array $data): User
    {
        return User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    // Autenticar usuário
    public function login(array $credentials): ?string
    {
        if (!$token = auth()->attempt($credentials)) {
            throw new InvalidCredentialsException('Credenciais inválidas.');
        }

        return $token;
    }

    // Remove autenticação usuário
    public function logout(): void
    {
        JWTAuth::invalidate(JWTAuth::getToken());
    }
}