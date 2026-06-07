<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        Order::all()->each(function (Order $order) {

            $tip = rand(0, 5);

            $discount = rand(0, 1)
                ? rand(0, 3)
                : 0;

            Payment::create([
                'order_id' => $order->id,
                'payment_date' => now()->subDays(rand(0, 30))->toDateString(),
                'payment_id' => 'PAY-'.fake()->unique()->numerify('#####'),
                'amount_due' => $order->total_price,
                'tips' => $tip,
                'discount' => $discount,
                'total_paid' => $order->total_price + $tip - $discount,
                'payment_type' => fake()->randomElement([
                    'Cash',
                    'Card',
                ]),
                'payment_status' => fake()->randomElement([
                    'Completed',
                    'Refunded',
                ]),
            ]);
        });
    }
}
