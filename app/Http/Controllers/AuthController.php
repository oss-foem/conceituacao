<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Repositories\UserRepository;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(
                ['error' => 'The provided credentials are invalid.'
            ], 401);
        }

        return response()->json(['token' => $token, 'user' => auth()->user()])
        ->cookie('jwt_token', $token, 60 * 24, '/', null, true, false);
    }

    public function logout()
    {
        try {
            $token = JWTAuth::getToken();

            if (!$token) {
                return response()->json([
                    'message' => 'User already logged out'], 401);
            }

        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }   catch (\Exception $e) {
            return response()->json(['message' => 'User already logged out']);
        }
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $user = $this->userRepository->create($data);

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }
}
