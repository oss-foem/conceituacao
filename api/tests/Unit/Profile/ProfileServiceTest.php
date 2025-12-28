<?php

namespace Tests\Unit;

use Tests\TestCase;
use Tests\Mocks\ProfileRepositoryMock;
use App\Domains\Profile\Services\ProfileService;
use App\Domains\Profile\Entities\Profile;

class ProfileServiceTest extends TestCase
{
    private ProfileRepositoryMock $profileRepository;
    private ProfileService $profileService;

    protected function setUp(): void
    {
        $this->profileRepository = new ProfileRepositoryMock();
        $this->profileService = new ProfileService($this->profileRepository);
    }

    public function test_get_all_profiles()
    {
        $this->profileService->create([
            'name' => 'Editor',
        ]);

        $profiles = $this->profileService->getAll();

        $this->assertCount(1, $profiles);
        $this->assertEquals('Editor', $profiles[0]->name);
    }

    public function test_find_profile_by_id()
    {
        $profile = $this->profileService->create([
            'name' => 'Teste',
        ]);

        $found = $this->profileService->getById($profile->id);

        $this->assertEquals($profile->id, $found->id);
        $this->assertEquals('Teste', $found->name);
    }

    public function test_create_new_profile()
    {
        $profile = $this->profileService->create([
            'name' => 'Subscriber',
        ]);

        $this->assertInstanceOf(Profile::class, $profile);
        $this->assertEquals('Subscriber', $profile->name);
    }

    public function test_update_profile()
    {
        $profile = $this->profileService->create([
            'name' => 'David',
        ]);

        $updated = $this->profileService->update($profile->id, ['name' => 'Dave']);

        $this->assertEquals('Dave', $updated->name);
        $this->assertEquals($profile->id, $updated->id);
    }

    public function test_delete_profile()
    {
        $profile = $this->profileService->create([
            'name' => 'David',
        ]);

        $this->profileService->delete($profile->id);

        $this->expectException(\Exception::class);
        $this->profileService->getById($profile->id);
    }


    protected function tearDown(): void
    {
        $this->profileRepository->clear();
        parent::tearDown();
    }
}
