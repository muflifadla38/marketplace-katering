<?php

namespace Database\Seeders;

use App\Enums\MenuCategories;
use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            [
                'name' => 'Ayam Geprek',
                'price' => 15000,
                'description' => 'Ayam goreng dengan bumbu khas',
            ],
            [
                'name' => 'Nasi Goreng',
                'price' => 20000,
                'description' => 'Nasi goreng dengan bumbu khas',
            ],
            [
                'name' => 'Jus Jeruk',
                'price' => 5000,
                'description' => 'Jus jeruk dengan rasa manis',
                'category' => MenuCategories::MINUMAN->value,
            ],
        ];

        foreach ($menus as $menu) {
            Menu::create(array_merge($menu, ['user_id' => 1]));
        }
    }
}
