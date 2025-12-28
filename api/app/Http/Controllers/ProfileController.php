<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Profile\Services\ProfileService;
use App\Http\Requests\Profile\StoreProfileRequest;
use App\Http\Requests\Profile\UpdateProfileRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct(private ProfileService $profileService) {}

    public function index(): JsonResponse
    {
        try {
            $profiles = $this->profileService->getAll();

            return response()->json([
                'message' => 'Lista de perfis recuperada com sucesso.',
                'data' => $profiles,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Erro ao recuperar lista de perfis.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $profile = $this->profileService->getById($id);

            return response()->json([
                'message' => 'Perfil recuperado com sucesso.',
                'data' => $profile,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Erro ao recuperar perfil.',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    public function store(StoreProfileRequest $request): JsonResponse
    {
        try {
            $data = $this->profileService->create($request->validated());

            return response()->json([
                'message' => 'Perfil criado com sucesso.',
                'data' => $data,
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Erro ao criar perfil.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(UpdateProfileRequest $request, int $id): JsonResponse
    {
        try {
            $data = $this->profileService->update($id, $request->validated());

            return response()->json([
                'message' => 'Perfil atualizado com sucesso.',
                'data' => $data,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Erro ao atualizar perfil.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->profileService->delete($id);

            return response()->json([
                'message' => 'Perfil deletado com sucesso.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Erro ao deletar perfil.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
