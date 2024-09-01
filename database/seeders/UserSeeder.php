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
        User::create([
            'user' => 'Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('admin1234'),
            'role' => 'admin'
        ]);
    }
}
