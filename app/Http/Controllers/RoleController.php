<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Repositories\RoleRepository;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;

class RoleController extends Controller
{
    private $repository;

    public function __construct(RoleRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $roles = $this->repository->getAll();

        return response()->json($roles);
    }

    public function store(StoreRoleRequest $request)
    {
        $data = $request->validated();
        $role = $this->repository->create($data);

        return response()->json([
            'message' => 'Role created successfully',
            'role' => $role
        ], 201);

    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        $data = $request->validated();
        $updatedRole = $this->repository->update($role, $data);

        return response()->json([
            'message' => 'Role updated successfully',
            'role' => $updatedRole
        ], 200);
    }

    public function destroy(Role $role)
    {
        $deletedRole = $this->repository->delete($role);

        return response()->json([
            'message' => 'Role deleted successfully',
            'role' => $deletedRole
        ], 200);
    }
}
