<?php

namespace App\Repositories;

use App\Http\Requests\UpdateUserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Request;


class UserRepository
{
    protected $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function getAll()
    {
        return $this->model->with('roles')->get();
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

    public function isAdmin(int $userId): bool
    {
        $adminRole = Role::where('name', 'admin')->first();

        if (!$adminRole) {
            $notAdmin =  "Is not admin";
            return false;
        }

        return $adminRole->user()->where('user_id', $userId)->exists();
    }

    public function find(int $userId)
    {
        return $this->model->with('roles')->find($userId);
    }

    public function show(User $user)
    {
        return response()->json($user);
    }

    public function create(array $data)
    {
        $data['password'] = bcrypt($data['password']);
        return $this->model->create($data);
    }

    public function update(int $userId, array $data)
    {
        $user = $this->model->findOrFail($userId);
        $user->update($data);

        return $user;
    }

    public function delete(int $id)
    {
        $user = $this->model->find($id);
        $user->delete();

        return $user;
    }

    public function assignRoles(int $userId, array $roleIds)
    {
        $user = $this->model->findOrFail($userId);
        $user->roles()->sync($roleIds, false);

        return $user;
    }

    public function removeRoles(int $userId, array $roleIds)
    {
        $user = $this->model->findOrFail($userId);
        $user->roles()->detach($roleIds);

        return $user;
    }
}
