<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\AssignRolesRequest;

class UserController extends Controller
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getUser()
    {
        return $this->userRepository->getUser();
    }

    public function index()
    {
        $users = $this->userRepository->getAll();
        return response()->json($users);
    }

    public function show(User $user)
    {
        $user->load('roles');
        return response()->json($user);
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $user = $this->userRepository->create($data);

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user
        ], 201);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();
        $user = $this->userRepository->update($user->id, $data);

        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user
        ], 200);
    }

    public function destroy(User $user)
    {
        $currentUser = auth('api')->user();

        if ($currentUser->id === $user->id && $this->userRepository->isAdmin($currentUser->id)) {
            return response()->json([
                'message' => 'Cannot delete yourself as an administrator'
            ], 403);
        }

        $this->userRepository->delete($user->id);

        return response()->json([
            'message' => 'User deleted successfully'
        ], 200);
    }

    public function assignRoles(AssignRolesRequest $request, User $user)
    {
        $data = $request->validated();
        $this->userRepository->assignRoles($user->id, $data['roles']);

        return response()->json([
            'message' => 'Roles assigned successfully',
            'user' => $user->fresh('roles')
        ]);
    }

    public function removeRoles(AssignRolesRequest $request, User $user)
    {
        $data = $request->validated();
        $this->userRepository->removeRoles($user->id, $data['roles']);

        return response()->json([
            'message' => 'Roles removed successfully',
            'user' => $user->fresh('roles')
        ]);
    }


    public function isAdmin(User $user)
    {
        $isAdmin = $this->userRepository->isAdmin($user->id);

        return response()->json([
            'isAdmin' => $isAdmin,
        ]);
    }


}
