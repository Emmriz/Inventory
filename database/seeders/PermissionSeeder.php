<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // User permissions
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
            
            // Inventory permissions
            'view_inventory',
            'create_items',
            'edit_items',
            'delete_items',
            
            // Department permissions
            'view_departments',
            'manage_departments',
            
            // Report permissions
            'view_reports',
            'export_reports',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);

        // Admin gets all permissions
        $adminRole->givePermissionTo(Permission::all());

        // User gets basic permissions
        $userRole->givePermissionTo([
            'view_inventory',
            'view_departments',
            'view_reports'
        ]);
    }
}