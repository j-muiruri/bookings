<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Admin',
                'email' => 'admin@test.com',
                'password' => Hash::make('admin1234'),
                'role' => 'admin'
            ],
            [
                'name' => 'User',
                'email' => 'user@test.com',
                'password' => Hash::make('user1234'),
                'role' => 'user'
            ]
        ];
        User::insert($data);
    }
}
