<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['email' => 'admin@gmail.com', 'name' => 'Admin', 'role' => 'admin'],
            ['email' => 'owner@bargain.local', 'name' => 'System Owner', 'role' => 'owner'],
            ['email' => 'manager@bargain.local', 'name' => 'Operations Manager', 'role' => 'manager'],
            ['email' => 'sales@bargain.local', 'name' => 'Counter Staff', 'role' => 'sales'],
            ['email' => 'books@bargain.local', 'name' => 'Bookkeeper', 'role' => 'bookkeeper'],
        ];

        foreach ($users as $row) {
            $user = User::query()->updateOrCreate(
                ['email' => $row['email']],
                [
                    'name' => $row['name'],
                    'password' => 'password',
                    'email_verified_at' => now(),
                ]
            );
            $user->roles()->detach();
            $user->assignRole($row['role']);
        }
    }
}
