<?php

namespace App\Repositories;

use App\Models\Role;
use App\Models\User;


class UserRepository
{
    protected $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function getUser()
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'message' => 'Usuário não autenticado'
            ], 401);
        }

        return response()->json($user);
    }

    public function isAdmin(User $user): bool
    {
        $adminRole = Role::where('name', 'Administrador')->first();

        if (!$adminRole) {
            return false;
        }

        return $user->roles->contains($adminRole);
    }

}
