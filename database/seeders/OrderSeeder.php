<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\ItemSize;
use App\Models\Order;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $orderNumber = 1000;

        Item::with('sizes')->get()->each(function ($item) use (&$orderNumber) {

            if ($item->sizes->isNotEmpty()) {

                foreach ($item->sizes as $size) {

                    $quantity = rand(1, 5);

                    Order::create([
                        'item_id' => $item->id,
                        'item_size_id' => $size->id,
                        'order_id' => 'ORD-'.$orderNumber,
                        'quantity' => $quantity,
                        'price' => $size->price,
                        'total_price' => $size->price * $quantity,
                        'status' => 'Completed',
                    ]);
                }
            } else {

                $defaultSize = ItemSize::where('item_id', $item->id)->first();

                $quantity = rand(1, 5);

                Order::create([
                    'item_id' => $item->id,
                    'item_size_id' => $defaultSize?->id,
                    'order_id' => 'ORD-'.$orderNumber,
                    'quantity' => $quantity,
                    'price' => $defaultSize?->price,
                    'total_price' => $defaultSize?->price * $quantity,
                    'status' => 'Completed',
                ]);
            }

            $orderNumber++;
        });
        $this->call(PaymentSeeder::class);
    }
}
