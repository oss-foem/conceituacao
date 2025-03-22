<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminRole
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle(Request $request, Closure $next): Response
    {
        $user = auth('api')->user();
        // dd($user);
        if (!$user || !$this->userRepository->isAdmin($user->id)) {
            return response()->json([
                'message' => 'Access denied. Only administrators can perform this operation.'
            ], 403);
        }

        return $next($request);
    }
}
