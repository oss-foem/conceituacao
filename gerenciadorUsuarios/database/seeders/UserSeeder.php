<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Profile;

class UserSeeder extends Seeder
{
    public function run()
    {
        $user = User::create([
            'name' => 'Administrator',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin'),
        ]);

        // Associa o usuário ao perfil "Administrador"
        $profile = Profile::where('name', 'Administrador')->first();
        if ($profile) {
            $user->profiles()->attach($profile->id);
        }
    }
}