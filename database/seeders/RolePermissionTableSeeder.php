<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RolePermission;

class RolePermissionTableSeeder extends Seeder
{
    public function run()
    {
        RolePermission::create(['role_id' => 1, 'permission_id' => 1]); // Admin can manage users
        RolePermission::create(['role_id' => 1, 'permission_id' => 2]); // Admin can manage customers
        RolePermission::create(['role_id' => 1, 'permission_id' => 3]); // Admin can manage products
        RolePermission::create(['role_id' => 1, 'permission_id' => 4]); // Admin can manage orders
        
        RolePermission::create(['role_id' => 2, 'permission_id' => 2]); // Saler can manage customers
        RolePermission::create(['role_id' => 2, 'permission_id' => 3]); // Saler can manage products
        RolePermission::create(['role_id' => 2, 'permission_id' => 4]); // Saler can manage orders
    }
}
