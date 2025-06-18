<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin User',
                'email' => 'admin@inventory.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'department_id' => 1,
            ],
            [
                'name' => 'Regular User',
                'email' => 'user@inventory.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'department_id' => 1,
            ],
        ]);
    }
}
