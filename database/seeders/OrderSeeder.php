<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $products = Product::all();

        if ($users->isEmpty() || $products->isEmpty()) {
            return;
        }

        $shippingSnapshots = [
            [
                'address'   => '123 King Fahd Rd',
                'address_2' => null,
                'phone'     => '+966501234567',
                'zip_code'  => '31982',
                'city'      => 'Dammam',
                'province'  => 'Eastern Province',
                'country'   => 'Saudi Arabia',
            ],
            [
                'address'   => '45 Olaya St',
                'address_2' => 'Apt 7',
                'phone'     => '+966509876543',
                'zip_code'  => '11564',
                'city'      => 'Riyadh',
                'province'  => 'Riyadh Province',
                'country'   => 'Saudi Arabia',
            ],
            [
                'address'   => '8 Al Corniche Blvd',
                'address_2' => null,
                'phone'     => '+966551122334',
                'zip_code'  => '21452',
                'city'      => 'Jeddah',
                'province'  => 'Makkah Province',
                'country'   => 'Saudi Arabia',
            ],
        ];

        $orderData = [
            [
                'user_index'      => 0,
                'shipping'        => 0,
                'payment_status'  => 'paid',
                'items'           => [
                    ['product_index' => 0, 'quantity' => 1],
                    ['product_index' => 4, 'quantity' => 1],
                ],
            ],
            [
                'user_index'      => 1,
                'shipping'        => 1,
                'payment_status'  => 'unpaid',
                'items'           => [
                    ['product_index' => 1, 'quantity' => 2],
                    ['product_index' => 6, 'quantity' => 1],
                    ['product_index' => 9, 'quantity' => 1],
                ],
            ],
            [
                'user_index'      => 2,
                'shipping'        => 2,
                'payment_status'  => 'paid',
                'items'           => [
                    ['product_index' => 2, 'quantity' => 1],
                    ['product_index' => 7, 'quantity' => 3],
                ],
            ],
            [
                'user_index'      => 0,
                'shipping'        => 0,
                'payment_status'  => 'unpaid',
                'items'           => [
                    ['product_index' => 10, 'quantity' => 2],
                    ['product_index' => 11, 'quantity' => 1],
                ],
            ],
            [
                'user_index'      => 3,
                'shipping'        => 1,
                'payment_status'  => 'paid',
                'items'           => [
                    ['product_index' => 3, 'quantity' => 1],
                    ['product_index' => 5, 'quantity' => 2],
                    ['product_index' => 8, 'quantity' => 1],
                ],
            ],
        ];

        foreach ($orderData as $data) {
            $user = $users->get($data['user_index'] % $users->count());

            $itemRows = [];
            $total = 0;

            foreach ($data['items'] as $item) {
                $product = $products->get($item['product_index'] % $products->count());
                $itemRows[] = [
                    'product'  => $product,
                    'quantity' => $item['quantity'],
                ];
                $total += $product->price * $item['quantity'];
            }

            $order = Order::create([
                'user_id'           => $user->id,
                'shipping_snapshot' => json_encode($shippingSnapshots[$data['shipping']]),
                'total_amount'      => $total,
                'payment_status'    => $data['payment_status'],
            ]);

            foreach ($itemRows as $row) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $row['product']->id,
                    'quantity'   => $row['quantity'],
                    'unit_price' => $row['product']->price,
                ]);
            }
        }
    }
}
