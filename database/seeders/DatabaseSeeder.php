<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserTableSeeder::class,
            RoleTableSeeder::class,
            ProductsTableSeeder::class,
            CustomersTableSeeder::class,
            UserRoleTableSeeder::class,
            PermissionTableSeeder::class,
            RolePermissionTableSeeder::class,
        ]);
    }
}
