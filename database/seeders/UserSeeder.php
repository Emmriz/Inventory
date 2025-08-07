<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::updateOrCreate([
            [
                'name' => 'Admin',
                'email' => 'emmanuel@ggcchq.org',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'department_id' => 1,
            ],
            
            
        ]);
    }
}
