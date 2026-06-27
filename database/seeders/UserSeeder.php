<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Seed users for local development and testing.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'John Admin',
                'email' => config('demo.email'),
                'role' => Role::Admin,
            ],
            [
                'name' => 'Sarah Manager',
                'email' => 'manager@example.com',
                'role' => Role::Manager,
            ],
            [
                'name' => 'Mike Smith',
                'email' => 'associate@example.com',
                'role' => Role::Associate,
            ],
        ];

        foreach ($users as $user) {
            User::query()->updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => 'password',
                    'role' => $user['role'],
                    'email_verified_at' => now(),
                ],
            );
        }
    }
}
