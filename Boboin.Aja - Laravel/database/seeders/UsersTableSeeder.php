<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Create Regular Users
        $regularUsers = [
            [
                'name' => 'Regular User',
                'email' => 'user@boboinaja.com',
                'password' => 'password',
            ],
            [
                'name' => 'Orang',
                'email' => 'orang@boboinaja.com',
                'password' => 'password',
            ],
        ];

        foreach ($regularUsers as $userData) {
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make($userData['password']),
                'is_admin' => false,
            ]);
            $user->assignRole('user');
        }

        // Create Admin Users
        $adminUsers = [
            [
                'name' => 'Ara Admin',
                'email' => 'ara@boboinaja.com',
                'password' => 'password',
            ],
            [
                'name' => 'Alja Admin',
                'email' => 'alja@boboinaja.com',
                'password' => 'password',
            ],
            [
                'name' => 'Pinkan Admin',
                'email' => 'pinkan@boboinaja.com',
                'password' => 'password',
            ],
            [
                'name' => 'Nanad Admin',
                'email' => 'nanad@boboinaja.com',
                'password' => 'password',
            ],
        ];

        foreach ($adminUsers as $adminData) {
            $admin = User::create([
                'name' => $adminData['name'],
                'email' => $adminData['email'],
                'password' => Hash::make($adminData['password']),
                'is_admin' => true,
            ]);
            $admin->assignRole('admin');
        }
    }
} 