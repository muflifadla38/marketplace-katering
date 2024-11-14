<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

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
            ],
            [
                'name' => 'Customer',
                'email' => 'customer@example.com',
            ],
        ];

        foreach ($users as $user) {
            User::factory()->create($user);
        }

        User::factory(10)->create();
    }
}
