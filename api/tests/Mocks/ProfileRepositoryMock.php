<?php

namespace Tests\Mocks;

use App\Domains\Profile\Entities\Profile;
use App\Domains\Profile\Contracts\ProfileRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ProfileRepositoryMock implements ProfileRepositoryInterface
{
    private array $profiles = [];
    private int $nextId = 1;

    public function create(array $data): Profile
    {
        $profile = new Profile($data);
        $profile->id = $this->nextId++;

        $this->profiles[] = $profile;

        return $profile;
    }

    public function all(): Collection
    {
        return new Collection($this->profiles);
    }

    public function findById(int $id): Profile
    {
        foreach ($this->profiles as $profile) {
            if ($profile->id === $id) {
                return $profile;
            }
        }

        throw new \Exception("Profile not found");
    }

    public function update(int $id, array $data): Profile
    {
        foreach ($this->profiles as &$profile) {
            if ($profile->id === $id) {
                foreach ($data as $key => $value) {
                    $profile->{$key} = $value;
                }
                return $profile;
            }
        }

        throw new \Exception("Profile not found");
    }

    public function delete(int $id): void
    {
        foreach ($this->profiles as $i => $profile) {
            if ($profile->id === $id) {
                unset($this->profiles[$i]);
                $this->profiles = array_values($this->profiles);
                return;
            }
        }

        throw new \Exception("Profile not found");
    }

    public function clear(): void
    {
        $this->profiles = [];
        $this->nextId = 1;
    }
}