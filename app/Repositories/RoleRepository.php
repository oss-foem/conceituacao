<?php

namespace App\Repositories;

use App\Models\Role;

class RoleRepository
{
    protected $model;

    public function __construct(Role $role)
    {
        $this->model = $role;
    }

    public function getAll()
    {
        return $this->model->with('user')->get();
    }

    public function find(int $roleId)
    {
        return $this->model->with('user')->find($roleId);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(Role $role, array $data)
    {
        $role->update($data);

        return $role;
    }

    public function delete(Role $role)
    {
        $role->delete();

        return $role;
    }
}
