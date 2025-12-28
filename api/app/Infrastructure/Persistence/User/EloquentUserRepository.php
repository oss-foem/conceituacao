<?php

namespace App\Infrastructure\Persistence\User;

use App\Domains\User\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use App\Domains\User\Entities\User;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function all(): Collection
    {
        return User::all();
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function findById(int $id): User
    {
        return User::findOrFail($id);
    }
    
    public function update(int $id, array $data): User
    {
        $user = User::findOrFail($id);
        $user->update($data);
        return $user;
    }
     
    public function delete(int $id): void
    {
        $user = User::findOrFail($id);
        $user->delete();
    }
}
