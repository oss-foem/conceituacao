<?php

namespace Tests\Unit;

use App\Domains\Auth\Services\AuthService;
use App\Domains\User\Entities\User;
use Tests\Mocks\UserRepositoryMock;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    private UserRepositoryMock $userRepositoryMock;
    private UserRegisterUseCase $UserRegisterUseCase;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->userRepositoryMock = new UserRepositoryMock();
        $this->authService = new AuthService($this->userRepositoryMock);
    }

    public function test_register_new_user_successfully()
    {
        $result = $this->authService->register([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
        ]);


        $user = $result['user'];
        $token = $result['token'];

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('john@example.com', $user->email);

        $this->assertIsString($token);
        $this->assertNotEmpty($token);
    }

    protected function tearDown(): void
    {
        $this->userRepositoryMock->clear();
        parent::tearDown();
    }
}