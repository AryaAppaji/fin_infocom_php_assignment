<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use App\Models\ItemSize;
use App\Models\Menu;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'name' => 'Item 1',
                'category' => 'Starters',
                'menu' => 'Food',
                'sizes' => [
                    [
                        'name' => 'Small',
                        'price' => 1.50,
                    ],
                    [
                        'name' => 'Large',
                        'price' => 2.50,
                    ],
                ],
            ],
            [
                'name' => 'Item 2',
                'category' => 'Starters',
                'menu' => 'Food',
                'price' => 3,
            ],
            [
                'name' => 'Item 3',
                'category' => 'Soft Drinks',
                'menu' => 'Drinks',
                'price' => 2.5,
            ],
            [
                'name' => 'Item 4',
                'category' => 'Soft Drinks',
                'menu' => 'Drinks',
                'price' => 1.50,
            ],
            [
                'name' => 'Item 5',
                'category' => 'Soft Drinks',
                'menu' => 'Drinks',
                'price' => 1.00,
            ],
            [
                'name' => 'Item 6',
                'category' => 'Mains',
                'menu' => 'Food',
                'sizes' => [
                    [
                        'name' => 'Small',
                        'price' => 2.50,
                    ],
                    [
                        'name' => 'Large',
                        'price' => 3.60,
                    ],
                ],
            ],
            [
                'name' => 'Item 7',
                'category' => 'Mains',
                'menu' => 'Food',
                'price' => 2.50,
            ],
            [
                'name' => 'Item 8',
                'category' => 'Desserts',
                'menu' => 'Drinks',
                'sizes' => [
                    [
                        'name' => 'Small',
                        'price' => 3.75,
                    ],
                    [
                        'name' => 'Large',
                        'price' => 6.50,
                    ],
                ],
            ],
            [
                'name' => 'Item 9',
                'category' => 'Desserts',
                'menu' => 'Drinks',
                'price' => 1.50,
            ],
            [
                'name' => 'Item 10',
                'category' => 'Hot Drinks',
                'menu' => 'Drinks',
                'price' => 2.00,
            ],
        ];

        foreach ($items as $item) {

            $menu = Menu::firstOrCreate([
                'name' => $item['menu'],
            ]);

            $category = Category::firstOrCreate([
                'name' => $item['category'],
                'menu_id' => $menu->id,
            ]);

            $itemModel = Item::firstOrCreate([
                'name' => $item['name'],
                'menu_id' => $menu->id,
                'category_id' => $category->id,
            ]);

            // Item with multiple sizes
            if (! empty($item['sizes'])) {

                foreach ($item['sizes'] as $size) {

                    ItemSize::firstOrCreate(
                        [
                            'item_id' => $itemModel->id,
                            'size' => $size['name'],
                        ],
                        [
                            'price' => $size['price'],
                        ]
                    );
                }

                continue;
            }

            // Item with a single/default price
            ItemSize::firstOrCreate(
                [
                    'item_id' => $itemModel->id,
                    'size' => null,
                ],
                [
                    'price' => $item['price'],
                ]
            );
        }

        $this->call(OrderSeeder::class);
    }
}
