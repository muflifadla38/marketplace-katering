<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Merchant',
                'email' => 'merchant@example.com',
                'role_id' => 1,
            ],
            [
                'name' => 'Customer',
                'email' => 'customer@example.com',
            ],
        ];

        foreach ($users as $user) {
            User::factory()->create($user);
        }
    }
}
