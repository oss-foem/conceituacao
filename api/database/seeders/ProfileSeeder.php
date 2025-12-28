<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domains\Profile\Entities\Profile;

class ProfileSeeder extends Seeder
{
    public function run(): void
    {
        Profile::create(['name' => 'Administrador']);
        Profile::create(['name' => 'Usuário']);
    }
}
