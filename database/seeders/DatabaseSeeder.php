<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create a test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password')
        ]);

        // Permissions exactly as in your form
        $permissions = [
            // Inventory
            'view_inventory',
            'create_inventory',
            'edit_inventory',
            'delete_inventory',

            // Items
            'view_item',
            'create_item',
            'edit_item',
            'delete_item',

            // Users
            'create_user',
            'edit_user',
            'delete_user',
            'view_user',

            // Departments
            'view_departments',
            'create_departments',
            'edit_departments',
            'delete_departments',

            // Roles
            'view_roles',
            'create_roles',
            'edit_roles',
            'delete_roles',

            // Permissions
            'view_permissions',
            'assign_permissions',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        // Roles
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $userRole  = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);

        // Give all permissions to admin
        $adminRole->syncPermissions(Permission::all());
    }
}
