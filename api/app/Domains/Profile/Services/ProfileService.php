<?php

namespace App\Domains\Profile\Services;

use App\Domains\Profile\Contracts\ProfileRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use App\Domains\Profile\Entities\Profile;

class ProfileService
{
    public function __construct(
        protected ProfileRepositoryInterface $profileRepository
    ) {}

    public function getAll(): Collection
    {
        return $this->profileRepository->all();
    }

    public function getById(int $id): Profile
    {
        return $this->profileRepository->findById($id);
    }

    public function create(array $data): Profile
    {
        return $this->profileRepository->create($data);
    }

    public function update(int $id, array $data): Profile
    {
        return $this->profileRepository->update($id, $data);
    }

    public function delete(int $id): void
    {
        $this->profileRepository->delete($id);
    }
}
