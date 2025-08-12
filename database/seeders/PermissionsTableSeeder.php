<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'create_user',
            'edit_user',
            'delete_user',
            'view_user',
            'create_department',
            'edit_department',
            'delete_department',
            'view_department',
            'create_item',
            'edit_item',
            'delete_item',
            'view_item',
            'create_report',
            'edit_report',
            'delete_report',
            'view_report',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
