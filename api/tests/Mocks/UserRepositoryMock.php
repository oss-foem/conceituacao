<?php

namespace Tests\Mocks;

use App\Domains\User\Entities\User;
use App\Domains\User\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class UserRepositoryMock implements UserRepositoryInterface
{
    private array $users = [];
    private int $nextId = 1;

    public function create(array $data): User
    {
        $user = new User($data);
        $user->id = $this->nextId++;

        $this->users[] = $user;

        return $user;
    }

    public function all(): Collection
    {
        return new Collection($this->users);
    }

    public function findById(int $id): User
    {
        foreach ($this->users as $user) {
            if ($user->id === $id) {
                return $user;
            }
        }

        throw new \Exception("User not found");
    }

    public function update(int $id, array $data): User
    {
        foreach ($this->users as &$user) {
            if ($user->id === $id) {
                foreach ($data as $key => $value) {
                    $user->{$key} = $value;
                }
                return $user;
            }
        }

        throw new \Exception("User not found");
    }

    public function delete(int $id): void
    {
        foreach ($this->users as $i => $user) {
            if ($user->id === $id) {
                unset($this->users[$i]);
                $this->users = array_values($this->users);
                return;
            }
        }

        throw new \Exception("User not found");
    }

    public function getUsers(): array
    {
        return $this->users;
    }

    public function clear(): void
    {
        $this->users = [];
        $this->nextId = 1;
    }
}