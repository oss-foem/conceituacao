<?php

namespace App\Infrastructure\Persistence\Profile;

use App\Domains\Profile\Contracts\ProfileRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use App\Domains\Profile\Entities\Profile;

class EloquentProfileRepository implements ProfileRepositoryInterface
{
    public function all(): Collection
    {
        return Profile::all();
    }

    public function findById(int $id): Profile
    {
        return Profile::findOrFail($id);
    }

    public function create(array $data): Profile
    {
        return Profile::create($data);
    }

    public function update(int $id, array $data): Profile
    {
        $profile = $this->findById($id);
        $profile->update($data);
        return $profile;
    }

    public function delete(int $id): void
    {
        $profile = $this->findById($id);
        $profile->delete();
    }
}
