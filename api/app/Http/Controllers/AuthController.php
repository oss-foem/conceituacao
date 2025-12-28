<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Domains\Auth\Services\AuthService;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            
            $data = $this->authService->register($request->validated());
            
            return response()->json([
                'message' => 'Usuário registrado com sucesso.',
                'data' => $data
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Erro ao registrar usuário.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $credentials = $request->only(['email', 'password']);

            $data = $this->authService->login($credentials);

            return response()->json([
                'message' => 'Login realizado com sucesso.',
                'data' => $data,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Erro ao realizar login.',
                'error' => $e->getMessage(),
            ], 401);
        }
    }

    public function logout(): JsonResponse
    {
        try {
            $this->authService->logout();

            return response()->json([
                'message' => 'Logout realizado com sucesso.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Erro ao realizar logout.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
