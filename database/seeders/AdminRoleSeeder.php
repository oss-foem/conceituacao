<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminUser = User::firstOrCreate([
            'email' => 'admin@example.com',
            [
                'name' => 'Administrator',
                'password' => bcrypt('password'),
            ],
        ]);

        $adminUser->roles()->syncWithoutDetaching([$adminRole->id]);
    }
}
