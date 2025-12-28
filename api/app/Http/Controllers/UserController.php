<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\User\Services\UserService;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Requests\User\AttachProfilesRequest;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(private UserService $userService) {}

    public function index(): JsonResponse
    {
        try {
            $users = $this->userService->getAll();

            return response()->json([
                'message' => 'Lista de usuários recuperada com sucesso.',
                'data' => $users,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Erro ao recuperar lista de usuários.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
      try {
          $user = $this->userService->getById($id);

          return response()->json([
              'message' => 'Usuário recuperado com sucesso.',
              'data' => $user,
          ]);
      } catch (\Throwable $e) {
          return response()->json([
              'message' => 'Erro ao recuperar usuário.',
              'error' => $e->getMessage(),
          ], 404);
      }
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        try {
            $data = $this->userService->create($request->validated());

            return response()->json([
                'message' => 'Usuário criado com sucesso.',
                'data' => $data,
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Erro ao criar usuário.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        try {
            $data = $this->userService->update($id, $request->validated());

            return response()->json([
                'message' => 'Usuário atualizado com sucesso.',
                'data' => $data,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Erro ao atualizar usuário.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->userService->delete($id);

            return response()->json([
                'message' => 'Usuário deletado com sucesso.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Erro ao deletar usuário.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function attachProfiles(AttachProfilesRequest $request, int $userId): JsonResponse
    {
        try {
            $this->userService->attachProfiles($userId, $request->profiles);

            return response()->json(['message' => 'Perfis associados com sucesso.']);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Erro ao associar perfis.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function detachProfiles(AttachProfilesRequest $request, int $userId): JsonResponse
    {
        try {
            $this->userService->detachProfiles($userId, $request->profiles);

            return response()->json(['message' => 'Perfis desassociados com sucesso.']);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Erro ao desassociar perfis.', 'error' => $e->getMessage()], 500);
        }
    }

    public function getProfiles(int $userId): JsonResponse
    {
        try {
            $profiles = $this->userService->getUserProfiles($userId);

            return response()->json(['message' => 'Perfis do usuário recuperados com sucesso.', 'data' => $profiles]);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Erro ao recuperar perfis do usuário.', 'error' => $e->getMessage()], 500);
        }
    }
}
