<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Menu;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Starters',
                'menu' => 'Food',
            ],
            [
                'name' => 'Soft Drinks',
                'menu' => 'Drinks',
            ],
            [
                'name' => 'Mains',
                'menu' => 'Food',
            ],
            [
                'name' => 'Desserts',
                'menu' => 'Drinks',
            ],
            [
                'name' => 'Hot Drinks',
                'menu' => 'Drinks',
            ],
        ];

        foreach ($categories as $category) {
            $menu = Menu::firstOrCreate(['name' => $category['menu']]);
            Category::firstOrCreate([
                'name' => $category['name'],
                'menu_id' => $menu->id,
            ]);
        }

        $this->call(ItemSeeder::class);
    }
}
