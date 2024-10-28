<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserRole;

class UserRoleTableSeeder extends Seeder
{
    public function run()
    {
        UserRole::create(['user_id' => 1, 'role_id' => 1]); // Admin
        UserRole::create(['user_id' => 1, 'role_id' => 2]); // Saler
    }
}
