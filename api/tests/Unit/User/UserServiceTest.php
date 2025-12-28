<?php

namespace Tests\Unit;

use Tests\TestCase;
use Tests\Mocks\UserRepositoryMock;
use App\Domains\User\Services\UserService;
use App\Domains\User\Entities\User;

class UserServiceTest extends TestCase
{
    private UserRepositoryMock $userRepository;
    private UserService $userService;

    protected function setUp(): void
    {
        $this->userRepository = new UserRepositoryMock();
        $this->userService = new UserService($this->userRepository);
    }

    public function test_get_all_users()
    {
        $this->userService->create([
            'name' => 'Bob',
            'email' => 'bob@example.com',
            'password' => 'secret',
        ]);

        $users = $this->userService->getAll();

        $this->assertCount(1, $users);
        $this->assertEquals('Bob', $users[0]->name);
    }

    public function test_find_user_by_id()
    {
        $user = $this->userService->create([
            'name' => 'Charlie',
            'email' => 'charlie@example.com',
            'password' => 'secret',
        ]);

        $found = $this->userService->getById($user->id);

        $this->assertEquals($user->id, $found->id);
        $this->assertEquals('Charlie', $found->name);
    }

    public function test_update_user()
    {
        $user = $this->userService->create([
            'name' => 'David',
            'email' => 'david@example.com',
            'password' => 'secret',
        ]);

        $updated = $this->userService->update($user->id, ['name' => 'Dave']);

        $this->assertEquals('Dave', $updated->name);
        $this->assertEquals($user->id, $updated->id);
    }

    public function test_delete_user()
    {
        $user = $this->userService->create([
            'name' => 'Eve',
            'email' => 'eve@example.com',
            'password' => 'secret',
        ]);

        $this->userService->delete($user->id);

        $this->expectException(\Exception::class);
        $this->userService->getById($user->id);
    }

    protected function tearDown(): void
    {
        $this->userRepository->clear();
        parent::tearDown();
    }
}
