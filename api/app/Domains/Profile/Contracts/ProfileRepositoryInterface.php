<?php

namespace App\Domains\Profile\Contracts;

use Illuminate\Database\Eloquent\Collection;
use App\Domains\Profile\Entities\Profile;

interface ProfileRepositoryInterface
{
    public function all(): Collection;
    public function findById(int $id): Profile;
    public function create(array $data): Profile;
    public function update(int $id, array $data): Profile;
    public function delete(int $id): void;
}
