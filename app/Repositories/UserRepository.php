<?php

namespace App\Repositories;

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
}
