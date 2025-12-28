<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Domains\Profile\Entities\Profile;
use App\Domains\User\Entities\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        $adminProfile = Profile::where('name', 'Administrador')->first();

        if ($adminProfile) {
            $user->profiles()->attach($adminProfile->id);
        }
    }
}