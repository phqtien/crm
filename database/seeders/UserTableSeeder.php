<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserTableSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin1@gmail.com',
            'password' => bcrypt('admin123'), // Mã hóa mật khẩu
        ]);

        User::create([
            'name' => 'Saler',
            'email' => 'saler1@gmail.com',
            'password' => bcrypt('saler123'), // Mã hóa mật khẩu
        ]);
    }
}
