<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    public function run()
    {
        Permission::create(['name' => 'users']);
        Permission::create(['name' => 'customers']);
        Permission::create(['name' => 'products']);
        Permission::create(['name' => 'orders']);
    }
}
