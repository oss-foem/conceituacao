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

        if (!$user || !$this->userRepository->isAdmin($user->id)) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'Access denied. Only administrators can perform this operation.'
                ], 403);
            } else {
                return redirect()->route('login.form')
                    ->with('error', 'Acesso negado. Apenas administradores podem acessar esta página.');
            }
        }

        return $next($request);
    }
}
