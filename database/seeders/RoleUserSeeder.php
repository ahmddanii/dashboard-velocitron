<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleUserSeeder extends Seeder
{
    public function run(): void
    {
        // Buat 5 role
        $roles = [
            'head-analytics',
            'financial-controller',
            'logistics-officer',
            'procurement-director',
            'key-account-manager',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        // Buat 5 user, masing-masing 1 role
        $users = [
            [
                'name'     => 'Muhammad Rizky Febrianto',
                'email'    => 'rizkyfb@superstore.com',
                'password' => Hash::make('password'),
                'role'     => 'head-analytics',
            ],
            [
                'name'     => 'Nur Ihsan',
                'email'    => 'ihsan@superstore.com',
                'password' => Hash::make('password'),
                'role'     => 'financial-controller',
            ],
            [
                'name'     => 'Dinathan Fahrezi',
                'email'    => 'nathanfz@superstore.com',
                'password' => Hash::make('password'),
                'role'     => 'logistics-officer',
            ],
            [
                'name'     => 'Ahmad Dani',
                'email'    => 'ahmddanii@superstore.com',
                'password' => Hash::make('password'),
                'role'     => 'procurement-director',
            ],
            [
                'name'     => 'Dhani Ahmad Prasetyo',
                'email'    => 'dhaniahmadp@superstore.com',
                'password' => Hash::make('password'),
                'role'     => 'key-account-manager',
            ],
        ];

        foreach ($users as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name'     => $data['name'],
                    'password' => $data['password'],
                ]
            );
            $user->assignRole($data['role']);
        }
    }
}
